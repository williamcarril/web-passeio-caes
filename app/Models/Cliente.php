<?php

namespace App\Models;

class Cliente extends Pessoa {

    protected $primaryKey = "idCliente";
    protected $table = 'cliente';

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Cancelamento", "pessoa", "cliente");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Passeio", "idCliente", "idCliente");
    }
}
