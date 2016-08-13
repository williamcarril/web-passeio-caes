<?php

namespace App\Models\Eloquent;

class Vacina extends \WGPC\Eloquent\Model {

    protected $table = "vacina";
    protected $primaryKey = "idVacina";
    protected $fillable = [
        "nome"
    ];
    protected static $rules = [
        "nome" => ["required", "max:30", "unique:vacina,nome", "string"]
    ];

    public function vacinacoes() {
        return $this->hasMany("\App\Models\Eloquent\Vacinacao", "idCao", "idCao");
    }

}
