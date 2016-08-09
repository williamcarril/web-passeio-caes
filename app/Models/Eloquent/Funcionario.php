<?php

namespace App\Models\Eloquent;

class Funcionario extends Pessoa {

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    protected static function boot() {
        parent::boot();
        
        static::$rules["idMultimidia"] = ["exists:multimidia,idMultimidia", "required", "integer"];
        static::$rules["rg"] = ["required", "string", "unique:funcionario,rg"];
        static::$rules["tipo"] = ["required", "in:passeador,administrador", "string"];
        static::$rules["email"][] = ["unique:funcionario,email"];
        static::$rules["cpf"][] = ["unique:funcionario,cpf"];
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

    public function passeios() {
        return $this->hasMany("\App\Models\Passeio", "idFuncionario", "idPasseador");
    }

    public function getAuthIdentifierName() {
        return "idFuncionario";
    }

}
