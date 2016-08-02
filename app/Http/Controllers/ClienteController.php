<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller {

    public function route_getCadastro(Request $req) {
        $data = [];

        return response()->view("cliente.cadastro", $data);
    }

    public function route_postLogin(Request $req) {
        return ["status" => true, "messages" => []];
    }

}
