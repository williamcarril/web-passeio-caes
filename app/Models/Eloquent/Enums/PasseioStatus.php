<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class PasseioStatus extends Enum {

    const PENDENTE = "pendente";
    const EM_ANDAMENTO = "em_andamento";
    const CANCELADO = "cancelado";
    const FEITO = "feito";
    const EM_ANALISE = "em_analise";

    public static function format($const) {
        switch ($const) {
            case static::PENDENTE:
            case static::CANCELADO:
            case static::FEITO:
                return ucfirst($const);
            case static::EM_ANDAMENTO:
                return "Em andamento";
            case static::EM_ANALISE:
                return "Em análise";
        }
    }

}
