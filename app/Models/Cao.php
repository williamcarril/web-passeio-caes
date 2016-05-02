<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cao extends Model {

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
        "nome" => ["required", "max:50"],
        "raca" => ["required", "max:25"],
        "porte" => ["required", "in:pequeno,medio,grande"],
        "genero" => ["in:macho,femea"],
        "idCliente" => ["required", "exists:cliente,idCliente"],
        "idMultimidia" => ["exists:multimidia,idMultimida"]
    ];

    public function cliente() {
        return $this->belongsTo("\App\Models\Cliente", "idCliente", "idCliente");
    }

    public function multimidia() {
        return $this->belongsTo("\App\Models\Multimidia", "idMultimidia", "idMultimidia");
    }

}
