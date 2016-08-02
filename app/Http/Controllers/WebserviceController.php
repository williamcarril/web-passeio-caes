<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address\CepResearcher;

class WebserviceController extends Controller {

    private $cepResearcher;

    public function __construct(CepResearcher $cepResearcher) {
        $this->cepResearcher = $cepResearcher;
    }

    public function route_getAddressByCep(Request $req) {
        $cep = $req->input("cep");
        $response = $this->cepResearcher->researchAddress($cep);
        return response()->json($response);
    }

}
