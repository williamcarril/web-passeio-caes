<?php

namespace App\Models\Eloquent;

use \App\Models\Eloquent\Enums\Porte;
use \App\Models\Eloquent\Enums\PasseioStatus as Status;
use \App\Models\Eloquent\Enums\AgendamentoStatus;

class Passeio extends \WGPC\Eloquent\Model {

    protected $table = "passeio";
    protected $primaryKey = "idPasseio";
    protected $fillable = [
        "idLocal",
        "idPasseador",
        "idPasseioOriginal",
        "inicio",
        "fim",
        "data",
        "status",
        "coletivo",
        "porte"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idLocal" => ["required", "exists:local,idLocal", "integer"],
        "idPasseador" => ["exists:funcionario,idFuncionario,tipo,passeador", "integer"],
        "idPasseioOriginal" => ["exists:passeio,idPasseio", "integer"],
        "coletivo" => ["boolean", "required"],
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "data" => ["required", "date"],
        "status" => ["required", "string"],
        "porte" => ["string"],
    ];
    protected $casts = [
        "precoPorCaoPorHora" => "float",
        "coletivo" => "boolean",
    ];
    protected $attributes = [
        "coletivo" => false,
        "status" => "pendente"
    ];

    public static function boot() {
        parent::boot();
        static::$rules["porte"][] = "in:" . implode(",", Porte::getConstants());
        static::$rules["status"][] = "in:" . implode(",", Status::getConstants());
    }

    public function agendamentos() {
        return $this->belongsToMany("\App\Models\Eloquent\Agendamento", "a_agendamento_passeio", "idPasseio", "idAgendamento");
    }

    public function passeioOriginal() {
        return $this->belongsTo("\App\Models\Eloquent\Passeio", "idPasseioOriginal", "idPasseio");
    }

    public function passeioRemarcado() {
        return $this->hasOne("\App\Models\Eloquent\Passeio", "idPasseio", "idPasseioOriginal");
    }
    
    public function local() {
        return $this->belongsTo("\App\Models\Eloquent\Local", "idLocal", "idLocal");
    }

    public function passeador() {
        return $this->belongsTo("\App\Models\Eloquent\Funcionario", "idPasseador", "idFuncionario");
    }

    public function cancelamentos() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento", "idPasseio", "idPasseio");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_cao_passeio", "idPasseio", "idCao")->withoutGlobalScope("ativo");
    }

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

    public function getClienteAttribute() {
        if (is_null($this->agendamento)) {
            return null;
        }
        return $this->agendamento->cliente;
    }

    public function getDonosAttribute() {
        $caes = $this->caes;
        $clientes = [];
        foreach ($caes as $cao) {
            $cliente = $cao->cliente;
            $clientes[$cliente->idCliente] = $cliente;
        }
        return array_values($clientes);
    }

    public function getModalidadeAttribute() {
        $agendamento = $this->agendamento()->first();
        if (is_null($agendamento)) {
            return null;
        }
        return $agendamento->modalidade;
    }

    public function scopeAgendamentoConfirmado($query) {
        return $query->whereHas("agendamento", function($q) {
                    $q->where("status", AgendamentoStatus::FEITO);
                });
    }

    public function scopePendente($query) {
        return $query->where("status", Status::PENDENTE);
    }

    public function scopeCancelado($query) {
        return $query->where("status", Status::CANCELADO);
    }

    public function scopeEmAndamento($query) {
        return $query->where("status", Status::EM_ANDAMENTO);
    }

    public function scopeFeito($query) {
        return $query->where("status", Status::FEITO);
    }

    public function scopeNaoCancelado($query) {
        return $query->where("status", "!=", Status::CANCELADO);
    }

    public function scopeNaoFinalizado($query) {
        return $query->where(function($q) {
                    $q->orWhere("status", Status::PENDENTE);
                    $q->orWhere("status", Status::EM_ANDAMENTO);
                });
    }

}
