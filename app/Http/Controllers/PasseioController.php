<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Passeio;
use App\Models\Eloquent\Cliente;
use App\Models\Eloquent\Funcionario;
use App\Models\Eloquent\Cancelamento;
use App\Models\Eloquent\Enums\PasseioStatus;
use App\Models\Eloquent\Enums\AgendamentoStatus;
use App\Models\Eloquent\Enums\CancelamentoStatus;
use App\Models\Eloquent\Enums\FuncionarioTipo;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class PasseioController extends Controller {

    private $auth;

    public function __construct(AuthFactory $auth) {
        $this->auth = $auth;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas do passeador">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getWalkerPasseio(Request $req, $id) {
        $passeador = $this->auth->guard("walker")->user();
        $passeio = $passeador->passeios()->where("idPasseio", $id)->firstOrFail();
        $data = [
            "passeio" => $passeio,
            "statusPasseio" => PasseioStatus::getConstants(false),
            "local" => $passeio->local,
            "caes" => $passeio->caes,
            "clientesConfirmados" => $passeio->getClientesConfirmados()
        ];
        return response()->view("walker.passeio.detalhes", $data);
    }

    public function route_getWalkerPasseiosConfirmados(Request $req) {
        $passeador = $this->auth->guard("walker")->user();
        $passeios = $passeador->passeios()->agendamentoConfirmado()->orderBy("data", "desc")->priorizarPorStatus()->get();
        $data = [
            "passeios" => $passeios,
            "statusPasseio" => PasseioStatus::getConstants(false)
        ];
        return response()->view("walker.passeio.listagem", $data);
    }

    public function route_getWalkerPasseioRotas(Request $req, $id) {
        $passeador = $this->auth->guard("walker")->user();
        $passeio = $passeador->passeios()->where("idPasseio", $id)->firstOrFail();
        $data = [
            "passeio" => $passeio,
            "statusPasseio" => PasseioStatus::getConstants(false),
            "local" => $passeio->local,
            "caes" => $passeio->caes,
            "clientesConfirmados" => $passeio->getClientesConfirmados()
        ];
        return response()->view("walker.passeio.rotas", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postWalkerCancelarPasseio(Request $req, $id) {
        $passeador = $this->auth->guard("walker")->user();
        $motivo = $req->input("motivo", null);
        $passeio = $passeador->passeios()->where("idPasseio", $id)->first();
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o passeio"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->cancelarPasseio($passeio, $passeador, $motivo)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $passeio->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postWalkerIniciarPasseio(Request $req, $id) {
        $passeador = $this->auth->guard("walker")->user();
        $passeio = $passeador->passeios()->where("idPasseio", $id)->first();
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o passeio"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->iniciarPasseio($passeio)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $passeio->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postWalkerFinalizarPasseio(Request $req, $id) {
        $passeador = $this->auth->guard("walker")->user();
        $passeio = $passeador->passeios()->where("idPasseio", $id)->first();
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o passeio"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->finalizarPasseio($passeio)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $passeio->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getPasseioDoCliente(Request $req, $id) {
        $cliente = $this->auth->guard("web")->user();
        $passeio = $cliente->passeios()->where("idPasseio", $id)->firstOrFail();
        $agendamento = $passeio->getAgendamentoDoCliente($cliente);
        $data = [
            "passeio" => $passeio,
            "statusPasseio" => PasseioStatus::getConstants(false),
            "local" => $passeio->local,
            "caes" => $passeio->caes,
            "agendamento" => $agendamento,
            "passeador" => $passeio->passeador
        ];
        return response()->view("cliente.passeio.detalhes", $data);
    }

    public function route_getPasseiosConfirmadosDoCliente(Request $req) {
        $cliente = $this->auth->guard("web")->user();
        $passeios = $cliente->passeiosConfirmados()->orderBy("data", "desc")->priorizarPorStatus()->get();
        $data = [
            "passeios" => $passeios,
            "statusPasseio" => PasseioStatus::getConstants(false)
        ];
        return response()->view("cliente.passeio.listagem", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getPasseiosJson(Request $req, $ano, $mes = null, $dia = null) {
        $coletivo = $req->input("coletivo", null);
        $discriminarLocalPorPorte = $req->input("discriminarPorte", null);
        if (!is_null($coletivo)) {
            $coletivo = filter_var($coletivo, FILTER_VALIDATE_BOOLEAN);
        }
        if (!is_null($discriminarLocalPorPorte)) {
            $discriminarLocalPorPorte = filter_var($discriminarLocalPorPorte, FILTER_VALIDATE_BOOLEAN);
        }
        $passeios = $this->getPasseiosByDataEColetividade($ano, $mes, $dia, $coletivo);
        $locais = collect();
        $passeios = $passeios->map(function($model) use ($locais, $discriminarLocalPorPorte) {
            $arr = $model->toArray();

            unset($arr["idAgendamento"]);
            unset($arr["idPasseador"]);
            unset($arr["idPasseioReagendado"]);
            unset($arr["precoPorCaoPorHora"]);
            $arr["tipo"] = $model->tipo;
            if (!$discriminarLocalPorPorte) {
                $locais[$model->local->idLocal] = $model->local->nome;
                $arr["local"] = $model->local->nome;
            } else {
                $locais["{$model->local->idLocal}{$model->porte}"] = "{$model->local->nome} ({$model->porteFormatado})";
                $arr["local"] = "{$model->local->nome} ({$model->porteFormatado})";
            }

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
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postCancelarPasseio(Request $req, $id) {
        $cliente = $this->auth->guard("web")->user();
        $motivo = $req->input("motivo", null);
        $passeio = $cliente->passeios()->where("idPasseio", $id)->first();
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o passeio"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->cancelarPasseio($passeio, $cliente, $motivo)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $passeio->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas administrativas">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminPasseio(Request $req, $id) {
        $passeio = Passeio::findOrFail($id);
        $passeadoresAptos = Funcionario::passeador()->where("idFuncionario", "!=", $passeio->idPasseador)->get();

        $data = [
            "passeio" => $passeio,
            "statusAgendamento" => AgendamentoStatus::getConstants(false),
            "statusPasseio" => PasseioStatus::getConstants(false),
            "statusCancelamento" => CancelamentoStatus::getConstants(false),
            "local" => $passeio->local,
            "caes" => $passeio->caes,
            "agendamentos" => $passeio->agendamentos,
            "passeador" => $passeio->passeador,
            "clientes" => $passeio->getClientesConfirmados(),
            "passeadoresAptos" => $passeadoresAptos,
            "cancelamentos" => $passeio->cancelamentos
        ];
        return response()->view("admin.passeio.detalhes", $data);
    }

    public function route_getAdminPasseiosMarcados(Request $req) {
        $status = $req->input("status", null);
        $dataInicial = $req->input("dataInicial", null);
        $dataFinal = $req->input("dataFinal", null);

        $passeios = Passeio::agendamentoConfirmado()->orderBy("data", "desc")->priorizarPorStatus();
        switch ($status) {
            case PasseioStatus::FEITO:
                $passeios->feito();
                break;
            case PasseioStatus::EM_ANALISE:
                $passeios->emAnalise();
                break;
            case PasseioStatus::EM_ANDAMENTO:
                $passeios->emAndamento();
                break;
            case PasseioStatus::CANCELADO:
                $passeios->cancelado();
                break;
            case PasseioStatus::PENDENTE:
                $passeios->pendente();
                break;
        }
        if (!empty($dataInicial)) {
            $dataInicial = str_replace("/", "-", $dataInicial);
            $passeios->where("data", ">=", date("Y-m-d", strtotime($dataInicial)));
        }
        if (!empty($dataFinal)) {
            $dataFinal = str_replace("/", "-", $dataFinal);
            $passeios->where("data", "<=", date("Y-m-d", strtotime($dataFinal)));
        }

        $data = [
            "passeios" => $passeios->get(),
            "statusPasseio" => PasseioStatus::getConstants(false),
            "status" => $status,
            "dataInicial" => $dataInicial,
            "dataFinal" => $dataFinal
        ];

        return response()->view("admin.passeio.listagem", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminMarcarComoFeito(Request $req, $id) {
        $passeio = Passeio::find($id);
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "marcar o passeio como feito"]));
        }
        if (!$passeio->checarStatus([PasseioStatus::PENDENTE, PasseioStatus::EM_ANDAMENTO])) {
            return $this->defaultJsonResponse(false, "Apenas passeios pendentes ou em andamento podem ser marcados como feitos.");
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

    public function route_postAdminAlocarPasseador(Request $req, $id) {
        $passeio = Passeio::find($id);
        $idPasseador = $req->input("idPasseador", null);
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "alocar o passeador"]));
        }

        if ($passeio->status !== PasseioStatus::PENDENTE) {
            return $this->defaultJsonResponse(false, "Somente um passeio pendente pode alocar/realocar/remover seu passeador.");
        }

        if (is_null($idPasseador)) {
            $passeio->idPasseador = null;
            if (!$passeio->save()) {
                return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "desalocar o passeador"]));
            }
            return $this->defaultJsonResponse(true);
        }

        $passeador = Funcionario::passeador()->where("idFuncionario", $idPasseador)->first();
        if ($passeador->conflitaComSeusPasseios($passeio)) {
            return $this->defaultJsonResponse(false, "O passeio em questão possui conflito de horário com os passeios do passeador.");
        }
        $passeio->idPasseador = $passeador->idFuncionario;
        if (!$passeio->save()) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "alocar o passeador"]));
        }

        $arrPasseador = $passeador->toArray();
        $arrPasseador["telefone"] = $passeador->telefoneFormatado;
        $arrPasseador["thumbnail"] = $passeador->thumbnail;

        return $this->defaultJsonResponse(true, null, $arrPasseador);
    }

    public function route_postAdminCancelarPasseio(Request $req, $id) {
        $administrador = $this->auth->guard("admin")->user();
        $motivo = $req->input("motivo", null);
        $passeio = Passeio::find($id);
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o passeio"]), null);
        }
        \DB::beginTransaction();
        try {
            $clientesConfirmados = $passeio->getClientesConfirmados();
            if (!$this->cancelarPasseio($passeio, $administrador, $motivo)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $passeio->getErrors());
            }
            foreach ($clientesConfirmados as $cliente) {
                \Mail::send("emails.cliente.passeio.cancelamento", [
                    'passeio' => $passeio,
                    "cliente" => $cliente
                        ], function ($m) use ($cliente) {
                    $m->to($cliente->email, $cliente->nome)->subject("Cancelamento de passeio");
                });
            }
            \DB::commit();
            return $this->defaultJsonResponse();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Outros métodos">
    public function iniciarPasseio(Passeio $passeio) {
        $agora = strtotime(date("Y-m-d H:i:s"));
        $inicio = strtotime("$passeio->data $passeio->inicio");
        $fim = strtotime("$passeio->data $passeio->fim");
        if ($inicio > $agora || $fim < $agora) {
            $passeio->putErrors(["Este passeio só pode ser iniciado no dia {$passeio->dataFormatada} entre {$passeio->inicioFormatado} e {$passeio->fimFormatado}."]);
            return false;
        }
        $passeio->status = PasseioStatus::EM_ANDAMENTO;
        return $passeio->save();
    }

    public function finalizarPasseio(Passeio $passeio) {
        if ($passeio->status !== PasseioStatus::EM_ANDAMENTO) {
            $passeio->putErrors(["Este passeio só pode ser finalizado caso esteja em andamento."]);
            return false;
        }
        $passeio->status = PasseioStatus::FEITO;
        return $passeio->save();
    }

    public function cancelarPasseio($passeio, $solicitante, $motivo, $agendamento = null) {
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
            if ($passeio->caes()->count() === 0) {
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
        $caes = $passeio->caes;
        if (!$passeio->coletivo) {
            $this->removerCaesDoPasseio($caes, $passeio);
            $passeio->status = PasseioStatus::CANCELADO;
            return $passeio->save();
        } else {
            //Se o parâmetro $agendamento for passado, cancela o passeio apenas para este agendamento deste cliente específico.
            //Se não, cancela este passeio para todos os agendamentos.
            if (!is_null($agendamento)) {
                $cliente = $agendamento->cliente;
                return $this->cancelarPasseioPorCliente($passeio, $cliente);
            } else {
                $this->removerCaesDoPasseio($caes, $passeio);
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
