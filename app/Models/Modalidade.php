<?php

namespace App\Models;

class Modalidade extends Model {
    protected $table = "modalidade";
    protected $primaryKey = "idModalidade";
    protected $fillable = [
        "nome",
        "descricao",
        "tipo"
    ];
    
    protected static $rules = [
        "nome" => ["required", "max:35"],
        "descricao" => ["required"],
        "tipo" => ["required", "in:pacote,unitario"],
    ];
    
}
