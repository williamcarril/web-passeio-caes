<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Enums\CancelamentoStatus;
use Illuminate\Http\Request;
use App\Models\Eloquent\Cancelamento;
use App\Models\Eloquent\Enums\PasseioStatus;
use App\Models\Eloquent\Enums\AgendamentoStatus;
use App\Models\Eloquent\Enums\FuncionarioTipo;

class CancelamentoController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas administrativas">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminCancelamentos(Request $req) {
        $solicitante = $req->input("solicitante", null);
        $cancelamentos = Cancelamento::priorizarPorStatus()->orderBy("data", "desc");
        switch ($solicitante) {
            case "administrador":
                $cancelamentos->where("tipoPessoa", "funcionario")
                        ->join("funcionario", "funcionario.idFuncionario", "=", "cancelamento.idPessoa")
                        ->where("funcionario.tipo", FuncionarioTipo::ADMINISTRADOR);
                break;
            case "passeador":
                $cancelamentos->where("tipoPessoa", "funcionario")
                        ->join("funcionario", "funcionario.idFuncionario", "=", "cancelamento.idPessoa")
                        ->where("funcionario.tipo", FuncionarioTipo::PASSEADOR);
                break;
            case "cliente":
                $cancelamentos->where("tipoPessoa", "cliente");
                break;
        }

        $data = [
            "cancelamentos" => $cancelamentos->get(),
            "statusCancelamento" => CancelamentoStatus::getConstants(false),
            "solicitante" => $solicitante
        ];
        return response()->view("admin.cancelamento.listagem", $data);
    }

    public function route_getAdminCancelamento(Request $req, $id) {
        $cancelamento = Cancelamento::findOrFail($id);
        $passeio = $cancelamento->passeio;
        $data = [
            "cancelamento" => $cancelamento,
            "solicitante" => $cancelamento->pessoa,
            "statusCancelamento" => CancelamentoStatus::getConstants(false),
            "passeio" => $passeio,
            "passeador" => $passeio->passeador,
            "caes" => $passeio->caes,
            "local" => $passeio->local,
            "statusAgendamento" => AgendamentoStatus::getConstants(false),
            "statusPasseio" => PasseioStatus::getConstants(false),
            "agendamentos" => $passeio->agendamentos,
            "clientes" => $passeio->getClientesConfirmados(),
        ];

        return response()->view("admin.cancelamento.detalhes", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminMarcarVisto(Request $req, $id) {
        $cancelamento = Cancelamento::find($id);
        if (is_null($cancelamento)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "marcar o cancelamento com visto"]));
        }
        $cancelamento->status = CancelamentoStatus::VERIFICADO;

        if (!$cancelamento->save()) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "marcar o cancelamento com visto"]));
        }

        return $this->defaultJsonResponse(true);
    }

    // </editor-fold>
    // </editor-fold>
}
