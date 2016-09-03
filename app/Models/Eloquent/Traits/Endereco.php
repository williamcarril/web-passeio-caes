<?php

namespace App\Models\Eloquent\Traits;

use App\Util\Formatter;

trait Endereco {

    public function getEndereco(array $components = ["logradouro", "bairro", "numero", "complemento", "postal"]) {
        $address = "";
        if (in_array("logradouro", $components) && !is_null($this->logradouro)) {
            $address .= "$this->logradouro, ";
        }
        if (in_array("bairro", $components) && !is_null($this->bairro)) {
            $address .= "$this->bairro, ";
        }
        if (in_array("numero", $components) && !is_null($this->numero)) {
            $address .= "$this->numero, ";
        }
        if (in_array("postal", $components) && !is_null($this->postal)) {
            $address .= "CEP: " . Formatter::cep($this->postal) . ", ";
        }
        if (in_array("complemento", $components) && !is_null($this->complemento)) {
            $address .= "$this->complemento, ";
        }

        return rtrim($address, ", ");
    }

}
