<?php

namespace App\Models\Eloquent;

class Modalidade extends \WGPC\Eloquent\Model {

    protected $table = "modalidade";
    protected $primaryKey = "idModalidade";
    protected $fillable = [
        "nome",
        "descricao",
        "tipo",
        "ativa",
        "coletivo",
        "preco"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "coletivo" => "boolean",
        "preco" => "float"
    ];
    protected $attributes = [
        "ativo" => true,
        "coletivo" => false
    ];
    protected static $rules = [
        "nome" => ["required", "string", "unique:modalidade,nome"],
        "descricao" => ["required", "string"],
        "tipo" => ["required", "in:pacote,unitario", "string"],
        "ativo" => ["required", "boolean"],
        "coletivo" => ["required", "boolean"],
        "preco" => ["required", "numeric"]
    ];

    public function valores() {
        return $this->hasMany("\App\Models\Eloquent\ModalidadeValor", "idModalidade", "idModalidade");
    }

}
