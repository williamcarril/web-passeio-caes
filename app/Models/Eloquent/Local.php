<?php

namespace App\Models\Eloquent;

use App\Util\Calculator;
use App\Util\Formatter;

class Local extends \WGPC\Eloquent\Model {

    use Traits\Endereco,
        Traits\Thumbnail;

    protected $table = "local";
    protected $primaryKey = "idLocal";
    protected $fillable = [
        "nome",
        "descricao",
        "raioAtuacao",
        "ativo",
        "logradouro",
        "bairro",
        "postal",
        "numero",
        "complemento",
        "lat",
        "lng",
        "slug"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "raioAtuacao" => "integer",
        "lat" => "float",
        "lng" => "float",
    ];
    protected static $rules = [
        "nome" => ["required", "max:70", "string", "unique:local,nome"],
        "raioAtuacao" => ["integer", "required"],
        "ativo" => ["boolean", "required"],
        "logradouro" => ["required", "max:70", "string"],
        "bairro" => ["required", "max:40", "string"],
        "postal" => ["required", "size:8", "string"],
        "numero" => ["max:12", "string"],
        "complemento" => ["max:50", "string"],
        "lat" => ["required", "numeric"],
        "lng" => ["required", "numeric"],
        "slug" => ["required", "string", "max:70"]
    ];

    public static function boot() {
        parent::boot();
        static::addGlobalScope("ativo", function(\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where("ativo", true);
        });
    }

    public static function getDefaultThumbnail() {
        return asset("img/place.png");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idLocal", "idLocal");
    }

    public function verificarServico($lat, $lng) {
        return $this->distanciaEntre($lat, $lng) <= $this->raioAtuacao;
    }

    public function imagens() {
        return $this->belongsToMany("\App\Models\Eloquent\Imagem", "a_local_imagem", "idLocal", "idImagem")
                        ->withPivot(["ordem"]);
    }

    public function distanciaEntre($lat, $lng) {
        return Calculator::distanceBetweenTwoCoordinates($this->lat, $this->lng, $lat, $lng);
    }

    public function getThumbnailAttribute() {
        $imagem = $this->imagens()->orderBy("ordem", "asc")->first();
        if (!is_null($imagem)) {
            return $imagem->getUrl();
        } else {
            return static::getDefaultThumbnail();
        }
    }

    public function setSlugAttribute($value) {
        $this->attributes["slug"] = str_slug($value, '-');
    }

    public function overrideNormalRules($rules) {
        $rules["slug"][] = "unique:local,slug,{$this->idLocal},idLocal";
        return $rules;
    }

    public function getCepFormatadoAttribute() {
        return Formatter::cep($this->postal);
    }

}
