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
        $mockedRequest = \Request::create(
                        route("passeio.data.json.get", [
                    "ano" => date("Y"),
                    "mes" => date("m")
                        ]), "GET", ["coletivo" => true]
        );

        $passeios = $this->getPasseiosByDataEColetividade(
                date("Y"), 
                date("m"),
                null,
                true);
//        $passeios = $this->getPasseiosByIntervaloEColetividade(
//                date("Y-m-d", strtotime("first day of last month")), 
//                date("Y-m-d", strtotime("last day of next month")), 
//                true);
        $data = [
            "local" => $local,
            "businessTimeStart" => config("general.businessTime.start"),
            "businessTimeEnd" => config("general.businessTime.end"),
            "passeios" => $passeios
        ];
        return response()->view("passeio.agenda", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getPasseiosJson(Request $req, $ano, $mes = null, $dia = null) {
        $coletivo = $req->input("coletivo", null);
        if(!is_null($coletivo)) {
            $coletivo = filter_var($coletivo, FILTER_VALIDATE_BOOLEAN);
        }
        $passeios = $this->getPasseiosByDataEColetividade($ano, $mes, $dia, $coletivo);
        $locais = collect();
        $passeios = $passeios->map(function($model) use ($locais) {
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
    // <editor-fold defaultstate="collapsed" desc="Métodos privados">

    private function getPasseiosByDataEColetividade($ano, $mes = null, $dia = null, $coletivo = null) {
        $passeios = Passeio::naoCancelado()
                ->agendamentoConfirmado()
                ->where(\DB::raw("YEAR(data)"), $ano);

        if (!is_null($mes)) {
            $passeios->where(\DB::raw("MONTH(data)"), $mes);
        }

        if (!is_null($dia)) {
            $passeios->where(\DB::raw("DAY(data)"), $dia);
        }

        if (!is_null($coletivo)) {
            $passeios->where("coletivo", $coletivo);
        }
        return $passeios
                        ->orderBy("data", "asc")
                        ->orderBy("inicio", "asc")->get();
    }

    private function getPasseiosByIntervaloEColetividade($intervaloInicial = null, $intervaloFinal = null, $coletivo = null) {
        $passeios = Passeio::naoCancelado()
                ->agendamentoConfirmado();
        if(!is_null($intervaloInicial)) {
            $passeios->where("data", ">=", $intervaloInicial);
        }
        if(!is_null($intervaloFinal)) {
            $passeios->where("data", "<=", $intervaloFinal);
        }

        if (!is_null($coletivo)) {
            $passeios->where("coletivo", $coletivo);
        }
        return $passeios
                        ->orderBy("data", "asc")
                        ->orderBy("inicio", "asc")->get();
    }
    // </editor-fold>
}
