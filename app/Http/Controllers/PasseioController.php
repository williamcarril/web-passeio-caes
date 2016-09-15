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
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getPasseiosJson(Request $req, $ano, $mes = null, $dia = null) {
        $passeios = Passeio::naoCancelado()
                ->agendamentoConfirmado()
                ->where(\DB::raw("YEAR(data)"), $ano);


        if (!empty($mes)) {
            $passeios->where(\DB::raw("MONTH(data)"), $mes);
        }

        if (!empty($dia)) {
            $passeios->where(\DB::raw("DAY(data)"), $dia);
        }
        $locais = collect();
        
        $passeios = $passeios
                ->orderBy("data", "asc")
                ->orderBy("inicio", "asc")->get()
                ->map(function($model) use ($locais) {
                    $arr = $model->toArray();
                    $model->load("agendamento.modalidade");

                    unset($arr["idAgendamento"]);
                    unset($arr["idLocal"]);
                    unset($arr["idPasseador"]);
                    unset($arr["idPasseioReagendado"]);
                    unset($arr["precoPorCaoPorHora"]);
                    $arr["modalidade"] = $model->agendamento->modalidade->nome;
                    $arr["local"] = $model->local->nome;
                    
                    $locais[$model->local->idLocal] = $model->local->nome;
                    
                    return $arr;
                });
        $locais = array_values($locais->toArray());
        $data = [
            "passeios" => $passeios,
            "locais" => $locais
        ];
        return $this->defaultJsonResponse(true, null, $data);
    }

    // </editor-fold>
}
