<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\AgendamentoStatus as Status;

class Agendamento extends \WGPC\Eloquent\Model {

    protected $table = "agendamento";
    protected $primaryKey = "idAgendamento";
    protected $fillable = [
        "idModalidade",
        "idCliente",
        "data",
        "idAgendamentoNovo",
        "status",
        "precoPorCaoPorHora"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "data" => ["required", "date"],
        "idAgendamentoNovo" => ["exists:agendamento,idAgendamento", "integer"],
        "precoPorCaoPorHora" => ["required", "numeric"],
        "status" => ["required", "string"]
    ];
    protected $dates = ["data"];
    protected $attributes = [
        "status" => Status::FUNCIONARIO
    ];

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            if(is_null($model->data)) {
                $model->data = date("Y-m-d H:i:s");
            }
        }, 1);

        static::$rules["status"][] = "in:" . implode(",", Status::getConstants());
    }

    public function passeios() {
        return $this->belongsToMany("\App\Models\Eloquent\Passeio", "a_agendamento_passeio", "idAgendamento", "idPasseio");
    }

    public function dias() {
        return $this->belongsToMany("\App\Models\Eloquent\Dia", "a_agendamento_dia", "idAgendamento", "idDia");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_agendamento_cao", "idAgendamento", "idCao")->withoutGlobalScope("ativo");
    }

    public function modalidade() {
        return $this->belongsTo("\App\Models\Eloquent\Modalidade", "idModalidade", "idModalidade")->withoutGlobalScope("ativo");
    }

    public function cliente() {
        return $this->belongsTo("\App\Models\Eloquent\Cliente", "idCliente", "idCliente")->withoutGlobalScope("ativo");
    }

    public function getStatusFormatadoAttribute() {
        if ($this->foiReagendado()) {
            return "Reagendado";
        }
        return Status::format($this->status);
    }

    public function foiReagendado() {
        return !is_null($this->idAgendamentoNovo);
    }
    
    public function getPrecoPorPasseioAttribute() {
        //Todos os passeios de um agendamento possuem a mesma duração.
        $passeio = $this->passeios()->first();
        if (is_null($passeio)) {
            return null;
        }
        return $this->caes()->count() * $this->precoPorCaoPorHora * $passeio->getDuracao();
    }

    public function getPrecoTotalAttribute() {
        $modalidade = $this->modalidade;
        $precoPorPasseio = $this->precoPorPasseio;
        if (is_null($modalidade) || is_null($precoPorPasseio)) {
            return null;
        }
        return $modalidade->quantidadeDePasseios * $precoPorPasseio;
    }

    public function getPrecoTotalFormatadoAttribute() {
        return "R$ " . number_format($this->precoTotal, 2, ",", ".");
    }

    public function getPrecoPorCaoPorHoraFormatadoAttribute() {
        return "R$ " . number_format($this->precoPorCaoPorHora, 2, ",", ".");
    }

    public function getDataFormatadaAttribute() {
        return date("d/m/Y", strtotime($this->data));
    }

    public function getHoraFormatadaAttribute() {
        return date("H:i:s", strtotime($this->data));
    }

    public function getDiasFormatadosAttribute() {
        $dias = $this->dias->map(function($dia) {
                    return $dia->nome;
                })->toArray();

        return str_lreplace(", ", " e ", implode(", ", $dias));
    }

    public function scopePriorizarPorStatus($query, $customPriorities = []) {
        $priorities = [Status::CLIENTE => 0, Status::FUNCIONARIO => 1, Status::CANCELADO => 2, Status::FEITO => 3];
        $priorities = array_merge($priorities, $customPriorities);
        $strPriority = "";
        foreach ($priorities as $priority => $value) {
            if (is_null($value)) {
                continue;
            }
            $strPriority .= "WHEN '$priority' THEN $value \n";
        }
        return $query->orderBy(
                        \DB::raw("CASE coalesce(status, 'null')
                            $strPriority
                            WHEN 'null' THEN 4
                    END"), "ASC");
    }

    public function scopePendente($query) {
        return $query->where(function($q) {
                    $q->orWhere("status", Status::FUNCIONARIO);
                    $q->orWhere("status", Status::CLIENTE);
                });
    }

    public function scopePendenteFuncionario($query) {
        return $query->where("status", Status::FUNCIONARIO);
    }

    public function scopePendenteCliente($query) {
        return $query->where("status", Status::CLIENTE);
    }

    public function scopeCancelado($query) {
        return $query->where("status", Status::CANCELADO);
    }

    public function scopeFeito($query) {
        return $query->where("status", Status::FEITO);
    }

    public function scopeNaoCancelado($query) {
        return $query->where("status", "!=", Status::CANCELADO);
    }

}
