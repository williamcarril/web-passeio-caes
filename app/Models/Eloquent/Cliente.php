<?php

namespace App\Models\Eloquent;

class Cliente extends Pessoa {

    protected $primaryKey = "idCliente";
    protected $table = 'cliente';

    public static function boot() {
        parent::boot();

        static::$rules["email"][] = "unique:cliente,email";
        static::$rules["cpf"][] = "unique:cliente,cpf";
    }

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Eloquent\Cancelamento", "pessoa", "cliente");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idCliente", "idCliente");
    }

    public function horariosDeInteresse() {
        return $this->hasMany("\App\Models\Eloquent\Horario", "idCliente", "idCliente");
    }

    public function getAuthIdentifierName() {
        return "idCliente";
    }

}
