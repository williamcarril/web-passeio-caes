<?php

namespace App\Models\Eloquent;

class Cao extends \WGPC\Eloquent\Model {

    protected $primaryKey = "idCao";
    protected $table = 'cao';
    protected $attributes = [
        "ativo" => true
    ];
    protected $fillable = [
        "nome",
        "raca",
        "porte",
        "genero",
        "idCliente",
        "idImagem",
        "ativo"
    ];
    protected $casts = ["ativo" => "boolean"];
    protected static $rules = [
        "nome" => ["required", "max:50", "string"],
        "raca" => ["required", "max:25", "string"],
        "porte" => ["required", "in:pequeno,medio,grande", "string"],
        "genero" => ["in:macho,femea", "string"],
        "ativo" => ["required", "boolean"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "idImagem" => ["exists:imagem,idImagem", "integer"]
    ];

    public function cliente() {
        return $this->belongsTo("\App\Models\Eloquent\Cliente", "idCliente", "idCliente");
    }

    public function imagem() {
        return $this->belongsTo("\App\Models\Eloquent\Imagem", "idImagem", "idImagem");
    }

    public function passeios() {
        return $this->belongsToMany("\App\Models\Eloquent\Passeio", "a_cao_passeio", "idCao", "idPasseio");
    }

    public function vacinacoes() {
        return $this->hasMany("\App\Models\Eloquent\Vacinacao", "idCao", "idCao");
    }

    public function vacinas() {
        return $this->hasManyThrough("\App\Models\Eloquent\Vacina", "\App\Models\Eloquent\Vacinacao", "idCao", "idVacina", "idCao");
    }

    public static function getDefaultThumbnail() {
        return asset("img/dog.png");
    }
    
    /**
     * @todo Definir thumbnail padrão
     */
    public function getThumbnailAttribute() {
        $imagem = $this->imagem;
        if (!is_null($imagem)) {
            return $imagem->getUrl();
        } else {
            return static::getDefaultThumbnail();
        }
    }

    public function scopeAtivo($query) {
        return $query->where('ativo', 1);
    }

}
