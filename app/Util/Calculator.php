<?php

namespace App\Util;

class Calculator {

    /**
     * Sobreescrita da função mágica de chamada de métodos estáticos para permitir o uso da 
     * classe App\Util\Calculator sem a instanciação de um objeto. A chamada do método estático
     * será delegada a uma instância adicionando o prefixo 'get' no nome do método.
     * Exemplo: Chamada do método Calculator::distance, resultará na chamada de 'getDistance' de uma instância
     * de App\Util\Calculator.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
        $instance = new self();
        return call_user_func_array([$instance, "get" . ucfirst($name)], $arguments);
    }

    /**
     * Utiliza a fórmula 'haversine' para calcular a menor distância entre dois pontos (ignorando obstáculos).
     * Cálculo obtido em: http://www.movable-type.co.uk/scripts/latlong.html.
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @param string $unit Unidade considerada (pode ser 'km' ou 'm')
     * @return float Distância em metros
     */
    public function getDistanceBetweenTwoCoordinates($lat1, $lng1, $lat2, $lng2, $unit = "m") {
        $R = 6371e3; // metres
        $φ1 = deg2rad($lat1);
        $φ2 = deg2rad($lat2);
        $Δφ = deg2rad($lat2 - $lat1);
        $Δλ = deg2rad($lng2 - $lng1);

        $a = sin($Δφ / 2) * sin($Δφ / 2) +
                cos($φ1) * cos($φ2) *
                sin($Δλ / 2) * sin($Δλ / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        switch ($unit) {
            case "km":
                return $d / 1000;
            case "m":
            default:
                return $d;
        }
    }

}
