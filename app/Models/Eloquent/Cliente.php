<?php

namespace App\Models\Eloquent;

class Cliente extends Pessoa {

    protected $primaryKey = "idCliente";
    protected $table = 'cliente';

    public static function boot() {
        parent::boot();

        static::$rules["email"][] = "unique:cliente,email";
    }

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Cancelamento", "pessoa", "cliente");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Passeio", "idCliente", "idCliente");
    }

}
