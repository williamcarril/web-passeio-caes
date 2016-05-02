<?php

namespace App\Models;

class Vacina extends Model {

    protected $table = "vacina";
    protected $primaryKey = "idVacina";
    protected $fillable = [
        "nome"
    ];
    protected static $rules = [
        "nome" => ["required", "max:30"]
    ];

    public function vacinacoes() {
        return $this->hasMany("\App\Models\Vacinacao", "idCao", "idCao");
    }

}
