<?php

namespace App\Models\Eloquent;

class ImagemArquivo extends \WGPC\Eloquent\Model {

    protected $table = "imagem_arquivo";
    protected $primaryKey = "idImagemArquivo";
    protected $fillable = [
        "idImagem",
        "tamanho",
        "arquivo"
    ];
    protected static $rules = [
        "idImagem" => ["required", "exists:imagem,idImagem", "integer"],
        "arquivo" => ["required", "max:255", "string"],
        "tamanho" => ["in:desktop,mobile"]
    ];

    public function getUrl() {
        return repository("$this->arquivo");
    }

    public function overrideNormalRules($rules) {
        $rules["tamanho"][] = "unique:imagem_arquivo,tamanho,{$this->idImagemArquivo},idImagemArquivo,tamanho,{$this->tamanho}";
        $rules["arquivo"][] = "unique:imagem_arquivo,arquivo,{$this->idImagemArquivo},idImagemArquivo";
        return $rules;
    }

}
