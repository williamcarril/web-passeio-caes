<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\AgendamentoStatus;

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

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            $model->data = date("Y-m-d H:i:s");
        }, 1);

        static::$rules["status"][] = implode(",", AgendamentoStatus::getConstants());
    }

    public function passeios() {
        return $this->belongsToMany("\App\Models\Eloquent\Passeio", "a_agendamento_passeio", "idAgendamento", "idPasseio");
    }

    public function dias() {
        return $this->belongsToMany("\App\Models\Eloquent\Dia", "a_agendamento_dia", "idAgendamento", "idDia");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_agendamento_cao", "idAgendamento", "idCao");
    }

    public function modalidade() {
        return $this->belongsTo("\App\Models\Eloquent\Modalidade", "idModalidade", "idModalidade");
    }

}
