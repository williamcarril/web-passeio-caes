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
        "idMultimidia"
    ];
    protected static $rules = [
        "nome" => ["required", "max:50", "string"],
        "raca" => ["required", "max:25", "string"],
        "porte" => ["required", "in:pequeno,medio,grande", "string"],
        "genero" => ["in:macho,femea", "string"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "idMultimidia" => ["exists:multimidia,idMultimida", "integer"]
    ];

    public function cliente() {
        return $this->belongsTo("\App\Models\Cliente", "idCliente", "idCliente");
    }

    public function multimidia() {
        return $this->belongsTo("\App\Models\Multimidia", "idMultimidia", "idMultimidia");
    }

    public function passeios() {
        return $this->belongsToMany("\App\Models\Passeio", "a_cao_passeio", "idCao", "idPasseio");
    }

    public function vacinacoes() {
        return $this->hasMany("\App\Models\Vacinacao", "idCao", "idCao");
    }
    
    public function vacinas() {
        return $this->hasManyThrough("\App\Models\Vacina", "\App\Models\Vacinacao", "idCao", "idVacina", "idCao");
    }

}
