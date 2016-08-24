<?php

namespace App\Models\Eloquent;

class Vacinacao extends \WGPC\Eloquent\Model {
    protected $table = "vacinacao";
    protected $primaryKey = "idVacinacao";
    
    protected $fillable = [
        "idCao",
        "idVacina",
        "aplicacao",
        "proximaAplicacao"
    ];
    
    protected static $rules = [
        "idCao" => ["required", "exists:cao,idCao", "integer"],
        "idVacina" => ["required", "exists:vacina,idVacina", "integer"],
        "aplicacao" => ["required", "date", "less:proximaAplicacao"],
        "proximaAplicacao" => ["date", "greater:aplicacao"]
    ];
    
    protected $dates = ["aplicacao", "proximaAplicacao"];
    
    public function cao() {
        return $this->belongsTo("\App\Models\Eloquent\Cao", "idCao", "idCao");
    }
    
    public function vacina() {
        return $this->belongsTo("\App\Models\Eloquent\Vacina", "idVacina", "idVacina");
    }
}
