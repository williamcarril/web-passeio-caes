<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Passeio;
use App\Models\Eloquent\Cliente;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class PasseioController extends Controller {

    private $auth;

    public function __construct(AuthFactory $auth) {
        $this->auth = $auth;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getPasseios() {
        $data = [];
        return response()->view("passeio.listagem", $data);
    }

    public function route_getPasseio(Request $req, $id) {
        $passeio = Passeio::findOrFail($id);
        $data = [
            "passeio" => $passeio
        ];
        return response()->view("passeio.detalhes", $data);
    }

    public function route_getAgenda(Request $req) {
        $local = $req->input("local", null);
        $data = [
            "local" => $local
        ];
        return response()->view("passeio.agenda", $data);
    }

    // </editor-fold>
}
