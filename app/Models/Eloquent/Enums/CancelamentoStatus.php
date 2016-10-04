<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class CancelamentoStatus extends Enum {

    const PENDETE = "pendente";
    const VERIFICADO = "verificado";

    public static function format($const) {
        return ucfirst($const);
    }
}
