<?php

namespace App\Models\Eloquent;

class Dia extends \WGPC\Eloquent\Model {
    protected $table = "dia";
    protected $primaryKey = "idDia";
    protected $guarded = ["nome"];
    
    protected static $rules = [
        "nome" => ["required", "max:13", "string", "unique:dia,nome"]
    ];

    public function horariosDeInteresse() {
        return $this->belongsToMany("\App\Models\Eloquent\Horario", "a_horario_dia", "idDia", "idHorario");
    }
}
