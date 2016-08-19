<?php

namespace App\Models\Eloquent;

class Cao extends \WGPC\Eloquent\Model {

    protected $primaryKey = "idCao";
    protected $table = 'cao';
    protected $fillable = [
        "idCao",
        "nome",
        "raca",
        "porte",
        "genero",
        "idCliente",
        "idImagem"
    ];
    protected static $rules = [
        "nome" => ["required", "max:50", "string"],
        "raca" => ["required", "max:25", "string"],
        "porte" => ["required", "in:pequeno,medio,grande", "string"],
        "genero" => ["in:macho,femea", "string"],
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

}
