<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class AgendamentoStatus extends Enum {

    const FEITO = "feito";
    const CANCELADO = "cancelado";
    const FUNCIONARIO = "pendente_funcionario";
    const CLIENTE = "pendente_cliente";

}
