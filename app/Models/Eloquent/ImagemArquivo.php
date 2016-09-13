<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\ImagemTamanho;

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
        "tamanho" => ["string"]
    ];

    public static function boot() {
        parent::boot();
        static::$rules["tamanho"][] = "in:" . ImagemTamanho::DESKTOP . "," . ImagemTamanho::MOBILE;
    }

    public function getUrl() {
        return repository("$this->arquivo");
    }

    public function overrideNormalRules($rules) {
        $rules["tamanho"][] = "unique:imagem_arquivo,tamanho,{$this->idImagemArquivo},idImagemArquivo,idImagem,{$this->idImagem}";
        $rules["arquivo"][] = "unique:imagem_arquivo,arquivo,{$this->idImagemArquivo},idImagemArquivo";
        return $rules;
    }

}
