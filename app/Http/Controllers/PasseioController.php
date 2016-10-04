<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Passeio;
use App\Models\Eloquent\Cliente;
use App\Models\Eloquent\Funcionario;
use App\Models\Eloquent\Cancelamento;
use App\Models\Eloquent\Enums\PasseioStatus;
use App\Models\Eloquent\Enums\AgendamentoStatus;
use App\Models\Eloquent\Enums\FuncionarioTipo;

class PasseioController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
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

    public function route_getPasseioJson(Request $req, $id) {
        $passeio = Passeio::find($id);
        return $this->defaultJsonResponse(true, null, $passeio);
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas administrativas">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminPasseio(Request $req, $id) {
        $passeio = Passeio::findOrFail($id);
        $data = [
            "passeio" => $passeio,
            "statusAgendamento" => AgendamentoStatus::getConstants(false),
            "statusPasseio" => PasseioStatus::getConstants(false),
            "local" => $passeio->local,
            "caes" => $passeio->caes,
            "agendamentos" => $passeio->agendamentos,
            "clientes" => $passeio->getClientesConfirmados()
        ];
        return response()->view("admin.passeio.detalhes", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminMarcarComoFeito(Request $req, $id) {
        $passeio = Passeio::find($id);
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "marcar o passeio como feito"]));
        }
        if ($passeio->status !== PasseioStatus::PENDENTE) {
            return $this->defaultJsonResponse(false, "Apenas passeios pendentes podem ser marcados como feitos.");
        }
        if (strtotime($passeio->data) >= strtotime(date("Y-m-d"))) {
            return $this->defaultJsonResponse(false, "Apenas passeios cujas datas são anteriores a de hoje podem ser marcados como feitos.");
        }
        $passeio->status = PasseioStatus::FEITO;
        if (!$passeio->save()) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "marcar o passeio como feito"]));
        }
        return $this->defaultJsonResponse(true);
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Outros métodos">
    public function cancelarPasseioParaAgendamento($passeio, $solicitante, $motivo, $agendamento) {
        //Se o passeio não estiver pendente de alguma forma, ou ele já foi cancelado, ou já foi concluído
        //ou já iniciado. Portanto, o cancelamento é impossível.
        if (!$passeio->checarStatus([PasseioStatus::PENDENTE, PasseioStatus::EM_ANALISE])) {
            return false;
        }

        $cancelamento = new Cancelamento();
        $cancelamento->justificativa = $motivo;
        if ($solicitante instanceof Cliente) {
            $cancelamento->tipoPessoa = "cliente";
            $cancelamento->idPessoa = $solicitante->idCliente;
        } elseif ($solicitante instanceof Funcionario) {
            $cancelamento->tipoPessoa = "funcionario";
            $cancelamento->idPessoa = $solicitante->idFuncionario;
        }
        $cancelamento->idPasseio = $passeio->idPasseio;
        if (!$cancelamento->save()) {
            return false;
        }
        //Caso o cancelamento tenha sido feito por um cliente, executa os seguintes procedimentos
        if ($solicitante instanceof Cliente) {
            return $this->cancelarPasseioPorCliente($passeio, $solicitante);
        } elseif ($solicitante instanceof Funcionario) {
            switch ($solicitante->tipo) {
                case FuncionarioTipo::ADMINISTRADOR:
                    return $this->cancelarPasseioPorAdministrador($passeio, $agendamento);
                case FuncionarioTipo::PASSEADOR:
                    return $this->cancelarPasseioPorPasseador($passeio);
            }
        }
        return false;
    }

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

    public function salvarPasseio($id = null, $dados) {
        if (is_null($id)) {
            $passeio = new Passeio();
        } else {
            $passeio = Passeio::find($id);
        }
        if (isset($dados["idLocal"])) {
            $passeio->idLocal = $dados["idLocal"];
        }
        if (isset($dados["inicio"])) {
            $passeio->inicio = $dados["inicio"];
        }
        if (isset($dados["fim"])) {
            $passeio->fim = $dados["fim"];
        }
        if (isset($dados["data"])) {
            $passeio->data = $dados["data"];
        }
        if (isset($dados["coletivo"])) {
            $passeio->coletivo = $dados["coletivo"];
        }
        if (isset($dados["porte"])) {
            $passeio->porte = $dados["porte"];
        }
        if (isset($dados["status"])) {
            $passeio->status = $dados["status"];
        }
        $passeio->save();
        return $passeio;
    }

    private function cancelarPasseioPorCliente($passeio, $cliente) {
        $caesDoCliente = $passeio->getCaesDoCliente($cliente->idCliente);
        $this->removerCaesDoPasseio($caesDoCliente, $passeio);

        if (!$passeio->coletivo) {
            $passeio->status = PasseioStatus::CANCELADO;
            return $passeio->save();
        } else {
            $qtdAgendamentosPendentes = $passeio->agendamentos()->whereHas("cliente", function($q) use ($cliente) {
                        $q->where("idCliente", "!=", $cliente->idCliente);
                    })->pendente()->count();
            //Verifica se há pessoas com solicitações para inclusão neste passeio e, caso positivo, não cancela o passeio
            if ($qtdAgendamentosPendentes > 1) {
                return true;
            }
            //Verifica se, após a remoção dos cães do cliente, sobrou algum para participar do passeio
            if($passeio->caes->count() === 0) {
                $passeio->status = PasseioStatus::CANCELADO;
                return $passeio->save();
            }
            return true;
        }
    }

    private function cancelarPasseioPorPasseador($passeio) {
        $passeio->idPasseador = null;
        return $passeio->save();
    }

    private function cancelarPasseioPorAdministrador($passeio, $agendamento = null) {
        if (!$passeio->coletivo) {
            $passeio->status = PasseioStatus::CANCELADO;
            return $passeio->save();
        } else {
            //Se o parâmetro $agendamento for passado, cancela o passeio apenas para este agendamento deste cliente específico.
            //Se não, cancela este passeio para todos os agendamentos.
            if (!is_null($agendamento)) {
                $cliente = $agendamento->cliente;
                return $this->cancelarPasseioPorCliente($passeio, $cliente);
            } else {
                $passeio->status = PasseioStatus::CANCELADO;
                return $passeio->save();
            }
        }
    }

    private function removerCaesDoPasseio($caes, $passeio) {
        $passeio->caes()->detach($caes);
        return true;
    }

    // </editor-fold>
}
