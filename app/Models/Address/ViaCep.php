<?php

namespace App\Models\Address;

class ViaCep implements CepResearcher {

    const URL = "viacep.com.br/ws/";
    const OUTPUT_FORMAT = "json";

    private $charset;

    public function __construct($charset = "unicode") {
        $this->charset = strtolower($charset);
    }

    public function isValid($cep) {
        $validator = validator(["cep" => $cep], ["cep" => "cep|required"]);
        return !$validator->fails();
    }

    public function researchAddress($cep) {
        if (!$this->isValid($cep)) {
            return ["status" => false, "messages" => [trans("validation.cep")], "data" => null];
        }
        $request = $this->request($cep);
        if (is_null($request)) {
            return ["status" => false, "messages" => [trans("webservice.generic.fails")], "data" => null];
        }

        $data = [
            "cep" => str_replace("-", "", $request["cep"]),
            "logradouro" => $request["logradouro"],
            "complemento" => $request["complemento"],
            "bairro" => $request["bairro"],
            "cidade" => $request["localidade"],
            "estado" => $request["uf"]
        ];
        return ["status" => true, "messages" => [], "data" => $data];
    }

    protected function request($cep) {
        $url = ViaCep::URL . "$cep/" . ViaCep::OUTPUT_FORMAT . "/$this->charset";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($status != 200) {
            return null;
        }
        $result = (array) json_decode($result);
        
        if (!empty($result["error"])) {
            return null;
        }

        return $result;
    }

}
