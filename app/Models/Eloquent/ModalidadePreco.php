<?php

namespace App\Models\Eloquent;

class ModalidadePreco extends \WGPC\Eloquent\Model {

    protected $table = "modalidade_preco";
    protected $primaryKey = ["idModalidade", "quantidadeCaes"];
    protected $fillable = [
        "idModalidade",
        "quantidadeCaes",
        "valor"
    ];
    protected $casts = [
        "quantidadeCaes" => "integer",
        "valor" => "float"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "integer", "exists:modalidade,idModalidade"],
        "quantidadeCaes" => ["required", "integer", "min:1"],
        "valor" => ["required", "numeric", "min:1"]
    ];

    public function modalidade() {
        return $this->belongsTo("\App\Models\Eloquent\Modalidade", "idModalidade", "idModalidade");
    }

}
