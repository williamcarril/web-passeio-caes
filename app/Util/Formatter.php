<?php

namespace App\Util;

class Formatter {

    /**
     * Sobreescrita da função mágica de chamada de métodos estáticos para permitir o uso da 
     * classe App\Util\Formatter sem a instanciação de um objeto. A chamada do método estático
     * será delegada a uma instância adicionando o prefixo 'format' no nome do método.
     * Exemplo: Chamada do método Formatter::cep, resultará na chamada de 'formatCep' de uma instância
     * de App\Util\Formatter.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
        $instance = new self();
        return call_user_func_array([$instance, "format" . ucfirst($name)], $arguments);
    }

    public function formatCep($cep) {
        if (strlen($cep) !== 8) {
            return $cep;
        }
        return substr($cep, 0, 5) . "-" . substr($cep, 5);
    }

    public function formatCpf($cpf) {
        if (strlen($cpf) !== 11) {
            return $cpf;
        }
        return substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9);
    }

    public function formatPhone($phone) {
        if (strlen($phone) > 11 && strlen($phone) < 10) {
            return $phone;
        }
        if (strlen($phone) === 11) {
            $sizeBeforeHyphen = 5;
        } else {
            $sizeBeforeHyphen = 4;
        }
        return "(" . substr($phone, 0, 2) . ") " . substr($phone, 2, $sizeBeforeHyphen) . "-" . substr($phone, $sizeBeforeHyphen + 2);
    }

}
