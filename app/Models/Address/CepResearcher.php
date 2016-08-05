<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Address;

interface CepResearcher {

    public function isValid($cep);

    /**
     * 
     * "cep" => $request["cep"],
     * "logradouro" => $request["logradouro"],
     * "complemento" => $request["complemento"],
     * "bairro" => $request["bairro"],
     * "cidade" => $request["localidade"],
     * "estado" => $request["uf"]
     * 
     * @param type $cep
     * @return
     */
    public function researchAddress($cep);
}
