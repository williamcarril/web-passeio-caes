<?php

namespace App\Models\Eloquent;

class Modalidade extends \WGPC\Eloquent\Model {

    protected $table = "modalidade";
    protected $primaryKey = "idModalidade";
    protected $fillable = [
        "nome",
        "descricao",
        "tipo",
        "periodo",
        "frequencia",
        "ativa",
        "coletivo"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "coletivo" => "boolean"
    ];
    protected $attributes = [
        "ativo" => true,
        "coletivo" => false
    ];
    protected static $rules = [
        "nome" => ["required", "string", "unique:modalidade,nome"],
        "descricao" => ["required", "string"],
        "tipo" => ["required", "in:pacote,unitario", "string"],
        "periodo" => ["required", "in:mensal,bimestral,trimestral,semestral,anual", "string"],
        "frequencia" => ["required", "in:semanal,bisemanal", "string"],
        "ativo" => ["required", "boolean"],
        "coletivo" => ["required", "boolean"]
    ];

    public function precos() {
        return $this->hasMany("\App\Models\Eloquent\ModalidadePreco", "idModalidade", "idModalidade");
    }

}
