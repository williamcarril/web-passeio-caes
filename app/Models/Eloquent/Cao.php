<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\Genero;
use App\Models\Eloquent\Enums\Porte;

class Cao extends \WGPC\Eloquent\Model {

    use Traits\Thumbnailable,
        Traits\Ativavel;

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
        "porte" => ["required", "string"],
        "genero" => ["required", "string"],
        "ativo" => ["required", "boolean"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "idImagem" => ["exists:imagem,idImagem", "integer"]
    ];

    public static function boot() {
        parent::boot();
        static::setStatusGlobalScope();
        static::$rules["genero"][] = "in:" . implode(",", Genero::getConstants());
        static::$rules["porte"][] = "in:" . implode(",", Porte::getConstants());
    }

    public function cliente() {
        return $this->belongsTo("\App\Models\Eloquent\Cliente", "idCliente", "idCliente");
    }

    public function imagem() {
        return $this->belongsTo("\App\Models\Eloquent\Imagem", "idImagem", "idImagem");
    }

    public function passeios() {
        return $this->belongsToMany("\App\Models\Eloquent\Passeio", "a_cao_passeio", "idCao", "idPasseio");
    }

    public static function getDefaultThumbnail() {
        return asset("img/dog.png");
    }

    public function getThumbnailAttribute() {
        $imagem = $this->imagem;
        if (!is_null($imagem)) {
            return $imagem->getUrl();
        } else {
            return static::getDefaultThumbnail();
        }
    }

    public function getPorteFormatadoAttribute() {
        return Porte::format($this->porte);
    }

    public function getGeneroFormatadoAttribute() {
        return Genero::format($this->genero);
    }
}
