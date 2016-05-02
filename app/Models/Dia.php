<?php

namespace App\Models;

class Dia extends Model {
    protected $table = "dia";
    protected $primaryKey = "idDia";
    protected $guarded = ["nome"];
    
    protected static $rules = [
        "nome" => ["required", "max:13"]
    ];

    public function horariosDeInteresse() {
        return $this->belongsToMany("App\Models\HorarioInteresse", "a_horario_interesse_dia", "idDia", "idHorarioInteresse")
                ->withPivot(["interesse"]);
    }
}
