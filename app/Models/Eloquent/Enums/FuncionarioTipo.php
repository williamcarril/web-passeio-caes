<?php

namespace App\Models\Eloquent\Enums;

abstract class FuncionarioTipo extends Enum {

    const PASSEADOR = "passeador";
    const ADMINISTRADOR = "administrador";

    public static function format($const) {
        return ucfirst($const);
    }
}
