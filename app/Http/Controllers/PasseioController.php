<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Passeio;
class PasseioController extends Controller {

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

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getPasseiosJson(Request $req, $ano, $mes = null, $dia = null) {
        $coletivo = $req->input("coletivo", null);
        if (!is_null($coletivo)) {
            $coletivo = filter_var($coletivo, FILTER_VALIDATE_BOOLEAN);
        }
        $passeios = $this->getPasseiosByDataEColetividade($ano, $mes, $dia, $coletivo);
        $locais = collect();
        $passeios = $passeios->map(function($model) use ($locais) {
            $arr = $model->toArray();

            unset($arr["idAgendamento"]);
            unset($arr["idLocal"]);
            unset($arr["idPasseador"]);
            unset($arr["idPasseioReagendado"]);
            unset($arr["precoPorCaoPorHora"]);
            $arr["local"] = $model->local->nome;
            $arr["tipo"] = $model->tipo;
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
    // <editor-fold defaultstate="collapsed" desc="Outros métodos">
    public function getPasseiosByDataEColetividade($ano, $mes = null, $dia = null, $coletivo = null) {
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

    public function getPasseiosByIntervaloEColetividade($intervaloInicial = null, $intervaloFinal = null, $coletivo = null) {
        $passeios = Passeio::naoCancelado()
                ->agendamentoConfirmado();
        if (!is_null($intervaloInicial)) {
            $passeios->where("data", ">=", $intervaloInicial);
        }
        if (!is_null($intervaloFinal)) {
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
