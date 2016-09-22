<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Modalidade;
use App\Models\Eloquent\Local;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AgendamentoController extends Controller {

    private $auth;
    private $passeioController;

    public function __construct(AuthFactory $auth, PasseioController $passeioController) {
        $this->auth = $auth;
        $this->passeioController = $passeioController;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAgenda(Request $req) {
        $local = $req->input("local", null);

        $passeios = $this->passeioController->getPasseiosByDataEColetividade(
                date("Y"), date("m"), null, true);
        $data = [
            "local" => $local,
            "businessStartingTime" => config("general.businessTime.start"),
            "businessEndingTime" => config("general.businessTime.end"),
            "passeios" => $passeios,
            "modalidades" => Modalidade::all(),
            "locais" => Local::all()
        ];
        return response()->view("passeio.agenda", $data);
    }

    // </editor-fold>
}
