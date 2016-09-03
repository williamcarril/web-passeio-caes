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
        return substr($cep, 0, 5) . "-" . substr($cep, 5);
    }

}
