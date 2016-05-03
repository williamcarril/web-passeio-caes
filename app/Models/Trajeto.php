<?php

namespace App\Models;

class Trajeto extends Model {

    protected $table = "trajeto";
    protected $primaryKey = "idTrajeto";
    protected $fillable = [
        "nome",
        "descricao",
        "raioAtuacao",
        "ativo",
        "rua",
        "bairro",
        "postal",
        "numero",
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
        "nome" => ["required", "max:70", "string"],
        "raioAtuacao" => ["integer", "required"],
        "ativo" => ["boolean", "required"],
        "rua" => ["required", "max:70", "string"],
        "bairro" => ["required", "max:40", "string"],
        "postal" => ["required", "size:8", "string"],
        "numero" => ["required", "max:12", "string"],
        "lat" => ["required", "numeric"],
        "lng" => ["required", "numeric"]
    ];

    public function passeios() {
        return $this->hasMany("\App\Models\Passeio", "idTrajeto", "idTrajeto");
    }

    public function verificarServico($lat, $lng) {
        $dX =  pow((pi() * $this->lat / 180) - (pi() * $lat / 180), 2);
        $dY =  pow((pi() * $this->lng / 180) - (pi() * $lng / 180), 2);

        return ((pow($this->raioAtuacao, 2)) >= ($dX + $dY));
    }

    public function fotos() {
        return $this->belongsToMany("\App\Models\Multimidia", "a_trajeto_foto", "idTrajeto", "idMultimidia")
                ->withPivot(["ordem"]);
    }
}
