<?php

namespace App\Models\Eloquent;

class Agendamento extends \WGPC\Eloquent\Model {

    protected $table = "agendamento";
    protected $primaryKey = "idAgendamento";
    protected $fillable = [
        "idModalidade",
        "idCliente",
        "data"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "data" => ["required", "date"]
    ];
    protected $dates = ["data"];

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }
    
    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idAgendamento", "idAgendamento");
    }
    
    public function dias() {
        return $this->belongsToMany("\App\Models\Eloquent\Dia", "a_agendamento_dia", "idAgendamento", "idDia");
    }

}
