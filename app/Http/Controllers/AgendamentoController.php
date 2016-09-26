<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Modalidade;
use App\Models\Eloquent\Dia;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AgendamentoController extends Controller {

    private $auth;
    private $passeioController;
    private $localController;

    public function __construct(AuthFactory $auth, PasseioController $passeioController, LocalController $localController) {
        $this->auth = $auth;
        $this->passeioController = $passeioController;
        $this->localController = $localController;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAgenda(Request $req) {
        $local = $req->input("local", null);
        
        $cliente = $this->auth->guard("web")->user();
        
        $passeios = $this->passeioController->getPasseiosByDataEColetividade(
                date("Y"), date("m"), null, true);
        
        $data = [
            "local" => $local,
            "businessStartingTime" => config("general.businessTime.start"),
            "businessEndingTime" => config("general.businessTime.end"),
            "passeios" => $passeios,
            "modalidades" => Modalidade::all(),
            "locais" => $this->localController->getLocaisAtuantesParaCliente($cliente),
            "dias" => Dia::orderBy("idDia", "asc")->get()->map(function($dia) {
                $arr = $dia->toArray();
                $arr["nome"] = $dia->nomeFormatado;
                return (object)$arr;
            }),
            "caes" => $cliente->caes
        ];
        return response()->view("passeio.agenda", $data);
    }

    // </editor-fold>
}
