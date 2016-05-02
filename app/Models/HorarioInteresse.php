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
        "inicio" => ["required", "date_format:H:i:s", "dataInicial"],
        "fim" => ["required", "date_format:H:i:s", "dataFinal"],
        "idCliente" => ["required", "exists:cliente,idCliente"]
    ];
    
    protected $attributes = [
        "inicio" => "00:00:00",
        "fim" => "23:59:59"
    ];
    
    protected $dates = ["inicio", "fim"];

    public static function boot() {
        parent::boot();
        static::$customRules = [
            "dataInicial" => function($attribute = null, $inicial = null, $parameters = [], $validator = null) {
                $data = $validator->getData();
                $final = isset($data["fim"]) ? $data["fim"] : null;
                if(is_null($final)) {
                    return true;
                }
                return $inicial <= $final;
            },
            "dataFinal" => function($attribute = [], $final = [], $parameters = [], $validator) {
                $data = $validator->getData();
                $inicial = isset($data["inicio"]) ? $data["inicio"] : null;
                if(is_null($inicial)) {
                    return true;
                }
                return $final >= $inicial;
            }
        ];
    }

}
