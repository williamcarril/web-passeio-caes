<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Eloquent\Enums;

abstract class Genero extends Enum {

    const MACHO = "macho";
    const FEMEA = "femea";

    public static function format($const) {
        switch ($const) {
            case static::MACHO:
                return "Macho";
            case static::FEMEA:
                return "Fêmea";
        }
    }

}
