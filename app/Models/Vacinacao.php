<?php

namespace App\Models;

class Vacinacao extends Model {
    protected $table = "vacinacao";
    protected $primaryKey = "idVacinacao";
    
    protected $fillabel = [
        "idCao",
        "idVacina",
        "aplicacao",
        "proximaAplicacao"
    ];
    
    protected static $rules = [
        "idCao" => ["required", "exists:cao,idCao"],
        "idVacina" => ["required", "exists:vacina,idVacina"],
        "aplicacao" => ["required", "date", "dataInicial:proximaAplicacao"],
        "proximaAplicacao" => ["date", "dataFinal:aplicacao"]
    ];
    
    protected $dates = ["aplicacao", "proximaAplicacao"];
    
    public function cao() {
        return $this->belongsTo("\App\Models\Cao", "idCao", "idCao");
    }
    
    public function vacina() {
        return $this->belongsTo("\App\Models\Vacina", "idVacina", "idVacina");
    }
}
