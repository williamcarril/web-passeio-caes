<?php

namespace App\Models\Eloquent;

class Agendamento extends \WGPC\Eloquent\Model {

    protected $table = "agendamento";
    protected $primaryKey = "idAgendamento";
    protected $fillable = [
        "idModalidade",
        "idCliente",
        "data",
        "idAgendamentoNovo",
        "status"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "data" => ["required", "date"],
        "idAgendamentoNovo" => ["exists:agendamento,idAgendamento", "integer"],
        "status" => ["required", "string", "in:feito,cancelado,pendente_funcionario,pendente_cliente"]
    ];
    protected $dates = ["data"];

    public static function boot() {
        parent::boot();

        static::saving(function($model) {
            $model->data = date("Y-m-d H:i:s");
        }, 1);
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idAgendamento", "idAgendamento");
    }

    public function dias() {
        return $this->belongsToMany("\App\Models\Eloquent\Dia", "a_agendamento_dia", "idAgendamento", "idDia");
    }
    
    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_agendamento_cao", "idAgendamento", "idCao");
    }

}
