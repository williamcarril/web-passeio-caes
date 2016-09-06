<?php

namespace App\Models\Eloquent;

class Funcionario extends Pessoa {

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    public static function boot() {
        parent::boot();

        static::$rules["idImagem"] = ["exists:imagem,idImagem", "required", "integer"];
        static::$rules["rg"] = ["required", "string", "unique:funcionario,rg"];
        static::$rules["tipo"] = ["required", "in:passeador,administrador", "string"];
    }

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        $this->fillable[] = "idImagem";
        $this->fillable[] = "rg";
        $this->fillable[] = "tipo";
    }

    public function foto() {
        return $this->belongsTo("\App\Models\Eloquent\Imagem", "idImagem", "idImagem");
    }

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Eloquent\Cancelamento", "pessoa", "funcionario");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idFuncionario", "idPasseador");
    }

    public function getAuthIdentifierName() {
        return "idFuncionario";
    }

    protected function overrideNormalRules($rules) {
        $rules["email"][] = "unique:funcionario,email,{$this->idFuncionario},idFuncionario";
        $rules["cpf"][] = "unique:funcionario,cpf,{$this->idFuncionario},idFuncionario";
        return $rules;
    }

}
