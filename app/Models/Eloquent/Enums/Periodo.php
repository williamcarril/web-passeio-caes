<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

class Periodo extends Enum {

    const MENSAL = "mensal";
    const BIMESTRAL = "bimestral";
    const TRIMESTRAL = "trimestral";
    const SEMESTRAL = "semestral";
    const ANUAL = "anual";

    public static function format($const) {
        return ucfirst($const);
    }

}
