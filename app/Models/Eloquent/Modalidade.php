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
        "coletivo",
        "precoPorPasseio"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "coletivo" => "boolean",
        "precoPorPasseio" => "float"
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
        "coletivo" => ["required", "boolean"],
        "precoPorPasseio" => ["required", "numeric"]
    ];

    public function valores() {
        return $this->hasMany("\App\Models\Eloquent\ModalidadeValor", "idModalidade", "idModalidade");
    }

}
