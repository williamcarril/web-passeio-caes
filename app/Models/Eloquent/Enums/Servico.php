<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class Servico extends Enum {

    const UNITARIO = "unitario";
    const PACOTE = "pacote";

    public static function format($const) {
        switch ($const) {
            case Servico::PACOTE:
                return "Pacote de passeios";
            case Servico::UNITARIO:
                return "Passeio unitário";
            default:
                return null;
        }
    }

}
