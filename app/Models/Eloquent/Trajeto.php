<?php

namespace App\Models\Eloquent;

class Trajeto extends \WGPC\Eloquent\Model {

    protected $table = "trajeto";
    protected $primaryKey = "idTrajeto";
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
        "lng"
    ];
    protected $casts = [
        "ativo" => "boolean",
        "raioAtuacao" => "integer",
        "lat" => "float",
        "lng" => "float",
    ];
    protected static $rules = [
        "nome" => ["required", "max:70", "string", "unique:trajeto,nome"],
        "raioAtuacao" => ["integer", "required"],
        "ativo" => ["boolean", "required"],
        "logradouro" => ["required", "max:70", "string"],
        "bairro" => ["required", "max:40", "string"],
        "postal" => ["required", "size:8", "string"],
        "numero" => ["required", "max:12", "string"],
        "complemento" => ["max:50", "string"],
        "lat" => ["required", "numeric"],
        "lng" => ["required", "numeric"]
    ];

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idTrajeto", "idTrajeto");
    }

    public function verificarServico($lat, $lng) {
        $dX =  pow((pi() * $this->lat / 180) - (pi() * $lat / 180), 2);
        $dY =  pow((pi() * $this->lng / 180) - (pi() * $lng / 180), 2);

        return ((pow($this->raioAtuacao, 2)) >= ($dX + $dY));
    }

    public function imagens() {
        return $this->belongsToMany("\App\Models\Eloquent\Imagem", "a_trajeto_imagem", "idTrajeto", "idImagem")
                ->withPivot(["ordem"]);
    }
}
