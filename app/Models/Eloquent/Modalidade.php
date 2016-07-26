<?php

namespace App\Models\Eloquent;

class Modalidade extends \WGPC\Eloquent\Model {
    protected $table = "modalidade";
    protected $primaryKey = "idModalidade";
    protected $fillable = [
        "nome",
        "descricao",
        "tipo"
    ];
    
    protected static $rules = [
        "nome" => ["required", "string", "unique:modalidade,nome"],
        "descricao" => ["required", "string"],
        "tipo" => ["required", "in:pacote,unitario", "string"],
    ];
    
}
