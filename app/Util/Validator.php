<?php

namespace App\Util;

class Validator {

    /**
     * Sobreescrita da função mágica de chamada de métodos estáticos para permitir o uso da 
     * classe App\Util\Validator sem a instanciação de um objeto. A chamada do método estático
     * será delegada a uma instância adicionando o prefixo 'check' no nome do método.
     * Exemplo: Chamada do método Validator::cpf, resultará na chamada de 'checkCpf' de uma instância
     * de App\Util\Validator.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments) {
        $instance = new self();
        return call_user_func_array([$instance, "check" . ucfirst($name)], $arguments);
    }

    /**
     * Valida se um CPF é válido ou não.
     * Sua assinatura foi adequada para extender as regras de validação do Validator nativo do Laravel.
     * @param string $attribute Nome do atributo validado (provavelmente será sempre 'cpf')
     * @param string $value CPF a ser validado
     * @param array $parameters Parâmetros adicionais passados
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkCpf($attribute = "", $value = "", $parameters = [], $validator = null) {
        $cpf = preg_replace('/[^0-9]/', '', $value);
        if (strlen($cpf) != 11) {
            return false;
        }
        if ($cpf == '00000000000' ||
                $cpf == '11111111111' ||
                $cpf == '22222222222' ||
                $cpf == '33333333333' ||
                $cpf == '44444444444' ||
                $cpf == '55555555555' ||
                $cpf == '66666666666' ||
                $cpf == '77777777777' ||
                $cpf == '88888888888' ||
                $cpf == '99999999999') {
            return false;
        }
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

    /**
     * Valida se um CEP é válido ou não.
     * Sua assinatura foi adequada para extender as regras de validação do Validator nativo do Laravel.
     * @param string $attribute Nome do atributo validado (provavelmente será sempre 'cpf')
     * @param string $value CEP a ser validado
     * @param array $parameters Parâmetros adicionais passados
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkCep($attribute = [], $value = [], $parameters = [], $validator = []) {
        $cep = preg_replace('/[^0-9]/', '', $value);
        return preg_match("/^[0-9]{8}$/", $cep);
    }

    /**
     * Verifica se um valor é o maior ou igual ao maior em um conjunto de valores.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Nome dos campos que serão confrontados em relação ao valor validado
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkGreaterOrEqualThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheGreatest = true;
        foreach ($parameters as $field) {
            $smallerOrEqualValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($smallerOrEqualValue)) {
                continue;
            }
            $isTheGreatest &= ($value >= $smallerOrEqualValue);
        }

        return $isTheGreatest;
    }
    public function checkDateGreaterOrEqualThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheGreatest = true;
        $value = strtotime($value);
        foreach ($parameters as $field) {
            $smallerOrEqualValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($smallerOrEqualValue)) {
                continue;
            }
            $isTheGreatest &= ($value >= strtotime($smallerOrEqualValue));
        }

        return $isTheGreatest;
    }

    /**
     * Verifica se um valor é o maior em um conjunto de valores.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Nome dos campos que serão confrontados em relação ao valor validado
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkGreaterThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheGreatest = true;
        foreach ($parameters as $field) {
            $smallerValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($smallerValue)) {
                continue;
            }
            $isTheGreatest &= ($value > $smallerValue);
        }

        return $isTheGreatest;
    }

    public function checkDateGreaterThanValue($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheGreatest = true;
        $value = strtotime($value);
        foreach ($parameters as $smallerValue) {
            if (is_null($smallerValue)) {
                continue;
            }
            $isTheGreatest &=  ($value > strtotime($smallerValue));
        }

        return $isTheGreatest;
    }

    /**
     * Verifica se um valor é o menor ou igual ao menor em um conjunto de valores.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Nome dos campos que serão confrontados em relação ao valor validado
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkLessThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheLeast = true;
        foreach ($parameters as $field) {
            $greaterValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($greaterValue)) {
                continue;
            }
            $isTheLeast &= ($value < $greaterValue);
        }

        return $isTheLeast;
    }

    public function checkDateLessThanValue($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheLeast = true;
        $value = strtotime($value);
        foreach ($parameters as $greaterValue) {
            if (is_null($greaterValue)) {
                continue;
            }
            $isTheLeast &= ($value < strtotime($greaterValue));
        }

        return $isTheLeast;
    }

    /**
     * Verifica se um valor é o menor em um conjunto de valores.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Nome dos campos que serão confrontados em relação ao valor validado
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkLessOrEqualThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheLeast = true;
        foreach ($parameters as $field) {
            $greaterValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($greaterValue)) {
                continue;
            }
            $isTheLeast &= ($value <= $greaterValue);
        }

        return $isTheLeast;
    }

    public function checkDateLessOrEqualThan($attribute = null, $value = null, $parameters = [], $validator = null) {
        $data = $validator->getData();
        $isTheLeast = true;
        $value = strtotime($value);
        foreach ($parameters as $field) {
            $greaterValue = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($greaterValue)) {
                continue;
            }
            $isTheLeast &= ($value <= strtotime($greaterValue));
        }

        return $isTheLeast;
    }

    /**
     * Verifica se um valor é um array de imagens.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Parâmetros adicionais. O primeiro será o tamanho máximo da imagem em kilobytes.
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkImageArray($attribute = null, $value = null, $parameters = [], $validator = null) {
        $maxSize = isset($parameters[0]) ? $parameters[0] : null;
        $imageRules = ["image"];
        if (!is_null($maxSize)) {
            $imageRules[] = "max:$maxSize";
        }
        if (!is_array($value)) {
            return false;
        }
        foreach ($value as $image) {
            $imageValidator = \validator($image, $imageRules);
            if ($imageValidator->fails()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verifica se o valor é um telefone.
     * @param string $attribute Nome do atributo a ser validado
     * @param mixed $value Valor a ser validado
     * @param array $parameters Parâmetros adicionais.
     * @param \Illuminate\Contracts\Validation\Validator $validator Instância do validador padrão do Laravel
     * @return boolean
     */
    public function checkPhone($attribute = null, $value = null, $parameter = [], $validator = null) {
        $phone = preg_replace('/[^0-9]/', '', $value);
        return preg_match("/^[0-9]{6}[0-9]{4}[0-9]?$/", $phone);
    }

}
