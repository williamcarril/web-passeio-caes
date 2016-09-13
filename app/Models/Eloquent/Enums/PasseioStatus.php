<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class PasseioStatus extends Enum {

    const PENDETE = "pendente";
    const EM_ANDAMENTO = "em_andamento";
    const CANCELADO = "cancelado";
    const FEITO = "feito";

}
