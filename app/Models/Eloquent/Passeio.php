<?php

namespace App\Models\Eloquent;

class Passeio extends \WGPC\Eloquent\Model {

    protected $table = "passeio";
    protected $primaryKey = "idPasseio";
    protected $fillable = [
        "idAgendamento",
        "idModalidade",
        "idTrajeto",
        "idPasseador",
        "idPasseioReagendado",
        "preco",
        "inicio",
        "fim",
        "data",
        "status",
        "coletivo"
    ];
    protected static $rules = [
        "idAgendamento" => ["required", "exists:agendamento,idAgendamento", "integer"],
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idTrajeto" => ["required", "exists:trajeto,idTrajeto", "integer"],
        "idPasseador" => ["exists:funcionario,idFuncionario,tipo,passeador", "integer"],
        "idPasseioReagendado" => ["exists:passeio,idPasseio", "integer"],
        "preco" => ["required", "numeric"],
        "coletivo" => ["boolean", "required"],
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "data" => ["required", "date"],
        "status" => ["required", "in:pendente,cancelado,em_andamento,feito", "string"]
    ];
    protected $dates = ["data"];
    protected $casts = [
        "preco" => "float",
        "coletivo" => "boolean"
    ];
    protected $attributes = [
        "coletivo" => false,
        "status" => "pendente"
    ];

    public function agendamento() {
        return $this->belongsTo("\App\Models\Eloquent\Agendamento", "idAgendamento", "idAgendamento");
    }

    public function passeioReagendado() {
        return $this->belongsTo("\App\Models\Eloquent\Passeio", "idPasseioReagendado", "idPasseio");
    }

    public function trajeto() {
        return $this->belongsTo("\App\Models\Eloquent\Trajeto", "idTrajeto", "idTrajeto");
    }

    public function passeador() {
        return $this->belongsTo("\App\Models\Eloquent\Funcionario", "idPasseador", "idFuncionario");
    }

    public function cancelamentos() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento", "idPasseio", "idPasseio");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_cao_passeio", "idPasseio", "idCao");
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

    public function scopePendente($query) {
        return $query->where('status', "pendente");
    }

    public function scopeCancelado($query) {
        return $query->where('status', "cancelado");
    }

    public function scopeEmAndamento($query) {
        return $query->where('status', "em_andamento");
    }

    public function scopeFeito($query) {
        return $query->where('status', "feito");
    }

    public function scopeNaoFinalizado($query) {
        return $query->where(function($q) {
                    $q->orWhere("status", "pendente");
                    $q->orWhere("status", "em_andamento");
                });
    }

}
