<?php

namespace App\Models\Eloquent;

class Dia extends \WGPC\Eloquent\Model {

    protected $table = "dia";
    protected $primaryKey = "idDia";
    protected $guarded = ["nome", "ordem"];
    protected static $rules = [
        "nome" => ["required", "max:13", "string", "unique:dia,nome"],
        "ordem" => ["required", "integer", "unique:dia,ordinal"]
    ];

    public static function boot() {
        parent::boot();

        static::addGlobalScope("ordenado", function(\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy("ordem", "asc");
        });
    }
    
    public function getNomeFormatadoAttribute() {
        return ucfirst($this->nome);
    }

    public function getCarbonName() {
        switch($this->nome) {
            case "domingo":
                return "sunday";
            case "segunda-feira":
                return "monday";
            case "terça-feira":
                return "tuesday";
            case "quarta-feira":
                return "wednesday";
            case "quinta-feira":
                return "thursday";
            case "sexta-feira":
                return "friday";
            case "sábado":
                return "saturday";
        }
    }
    
}
