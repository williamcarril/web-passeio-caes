<?php

namespace App\Models\Eloquent;

class Horario extends \WGPC\Eloquent\Model {

    protected $table = "horario";
    protected $primaryKey = "idHorario";
    protected $fillable = [
        "inicio",
        "fim",
        "idCliente"
    ];
    
    protected static $rules = [
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"]
    ];
    
    protected $attributes = [
        "inicio" => "00:00:00",
        "fim" => "23:59:59"
    ];
    
    public function dias() {
        return $this->belongsToMany("\App\Models\Eloquent\Dia", "a_horario_dia", "idHorario", "idDia");
    }

}
