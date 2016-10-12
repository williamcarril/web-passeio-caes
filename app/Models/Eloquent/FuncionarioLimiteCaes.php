<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\FuncionarioTipo;
use App\Models\Eloquent\Enums\Porte;

class FuncionarioLimiteCaes extends \WGPC\Eloquent\Model {

    protected $table = "funcionario_limite_caes";
    protected $primaryKey = "idFuncionario";
    protected static $rules = [
        "idFuncionario" => ["integer", "exists:funcionario,idFuncionario,tipo,passeador"],
        "porte" => ["required", "string"],
        "limite" => ["required", "integer", "min:1"]
    ];

    public static function boot() {
        parent::boot();

        static::$rules["porte"][] = "in:" . implode(",", Porte::getConstants());
        
        
        static::addGlobalScope("ordem", function(\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy(
                        \DB::raw("CASE coalesce(porte, 'null')
                            WHEN '" . Porte::PEQUENO . "' THEN 1
                            WHEN '" . Porte::MEDIO . "' THEN 2
                            WHEN '" . Porte::GRANDE . "' THEN 3
                            WHEN 'null' THEN 4
                    END"), "ASC");
        });
    }

    public function funcionario() {
        return $this->belongsTo("\App\Models\Eloquent\Funcionario", "idFuncionario", "idFuncionario");
    }

    public function scopePorte($query, $porte = null) {
        return $query->where("porte", $porte);
    }

    public function scopePequeno($query) {
        return $query->where("porte", Porte::PEQUENO);
    }

    public function scopeMedio($query) {
        return $query->where("porte", Porte::MEDIO);
    }

    public function scopeGrande($query) {
        return $query->where("porte", Porte::GRANDE);
    }
    
    public function getPorteFormatadoAttribute() {
        return Porte::format($this->porte);
    }

}
