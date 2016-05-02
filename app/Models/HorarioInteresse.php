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
        "inicio" => ["required", "date_format:H:i:s", "dataInicial:fim"],
        "fim" => ["required", "date_format:H:i:s", "dataFinal:inicio"],
        "idCliente" => ["required", "exists:cliente,idCliente"]
    ];
    
    protected $attributes = [
        "inicio" => "00:00:00",
        "fim" => "23:59:59"
    ];
    
    protected $dates = ["inicio", "fim"];
    
    public function dias() {
        return $this->belongsToMany("App\Models\Dia", "a_horario_interesse_dia", "idHorarioInteresse", "idDia")
                ->withPivot(["interesse"]);
    }

}
