<?php

namespace App\Models;

class Funcionario extends Pessoa {

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    protected static function boot() {
        parent::boot();
        
        static::$rules["idMultimidia"] = ["exists:multimidia,idMultimidia", "required"];
        static::$rules["rg"] = ["required"];
        static::$rules["tipo"] = ["required", "in:passeador,administrador"];
    }

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        $this->fillable[] = "idMultimidia";
        $this->fillable[] = "rg";
        $this->fillable[] = "tipo";
    }

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Cancelamento", "pessoa", "funcionario");
    }

}
