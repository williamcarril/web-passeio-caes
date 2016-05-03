<?php

namespace App\Models;
use \Carbon\Carbon;

class HorarioInteresse extends Model {

    protected $table = "horario_interesse";
    protected $primaryKey = "idHorarioInteresse";
    protected $fillable = [
        "idHorarioInteresse",
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
        return $this->belongsToMany("App\Models\Dia", "a_horario_interesse_dia", "idHorarioInteresse", "idDia")
                ->withPivot(["interesse"]);
    }

}
