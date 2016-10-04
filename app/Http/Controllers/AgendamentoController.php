<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Agendamento;
use App\Models\Eloquent\Pessoa;
use App\Models\Eloquent\Passeio;
use App\Models\Eloquent\Cao;
use App\Models\Eloquent\Local;
use App\Models\Eloquent\Modalidade;
use App\Models\Eloquent\Enums\AgendamentoStatus;
use App\Models\Eloquent\Enums\PasseioStatus;
use App\Models\Eloquent\Enums\Servico;
use App\Models\Eloquent\Enums\Ids\Modalidade as ModalidadeIds;
use App\Models\Eloquent\Dia;
use Carbon\Carbon;
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

    // <editor-fold defaultstate="collapsed" desc="Rotas da área administrativas">
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que  retornam Views">
    public function route_getAdminAgendamentos(Request $req) {
        $filtro = $req->input("filtro", null);

        $agendamentos = Agendamento::orderBy("data", "desc")->priorizarPorStatus([
            AgendamentoStatus::FUNCIONARIO => 0,
            AgendamentoStatus::CLIENTE => 1
        ]);

        if (!empty($filtro)) {
            switch ($filtro) {
                case "CLIENTE":
                    $agendamentos->pendenteCliente();
                    break;
                case "FUNCIONARIO":
                    $agendamentos->pendenteFuncionario();
                    break;
                case "FEITO":
                    $agendamentos->feito();
                    break;
                case "CANCELADO":
                    $agendamentos->cancelado();
                    break;
            }
        }

        $data = [
            "agendamentos" => $agendamentos->get(),
            "statusAgendamento" => AgendamentoStatus::getConstants(false),
            "filtro" => $filtro
        ];
        return response()->view("admin.agendamento.listagem", $data);
    }

    public function route_getAdminAgendamento(Request $req, $id) {
        $agendamento = Agendamento::findOrFail($id);
        $data = [
            "agendamento" => $agendamento,
            "statusAgendamento" => AgendamentoStatus::getConstants(false),
            "statusPasseio" => PasseioStatus::getConstants(false),
            "local" => $agendamento->passeios()->first()->local,
            "caes" => $agendamento->caes,
            "modalidade" => $agendamento->modalidade,
            "passeios" => $agendamento->passeios,
            "customer" => $agendamento->cliente
        ];

        return response()->view("admin.agendamento.detalhes", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminAceitarAgendamento(Request $req, $id) {
        $administrador = $this->auth->guard("admin")->user();
        $agendamento = Agendamento::where("idAgendamento", $id)->first();
        if (is_null($agendamento)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "aceitar o agendamento"]));
        }

        $primeiroPasseio = $agendamento->passeios()->orderBy("data", "asc")->first();
        if (date("Y-m-d") >= $primeiroPasseio->data) {
            \DB::beginTransaction();
            try {
                if (!$this->cancelarAgendamento($agendamento, $administrador, "Data do primeiro passeio expirada.")) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "aceitar o agendamento"]));
                }
                \DB::commit();
                return $this->defaultJsonResponse(false, "A data do primeiro passeio programado já passou. "
                                . "Portanto, o agendamento em questão é considerado inválido e foi cancelado.", [
                            "messageLevel" => "warning",
                            "redirect" => true
                ]);
            } catch (\Exception $ex) {
                \DB::rollBack();
                throw $ex;
            }
        }

        \DB::beginTransaction();
        try {
            if (!$this->aceitarAgendamento($agendamento, $administrador)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postAdminCancelarAgendamento(Request $req, $id) {
        $administrador = $this->auth->guard("admin")->user();
        $motivo = $req->input("motivo", null);
        $agendamento = Agendamento::where("idAgendamento", $id)->first();
        if (is_null($agendamento)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o agendamento"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->cancelarAgendamento($agendamento, $administrador, $motivo)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
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
    public function route_getAgendamento(Request $req) {
        $idLocal = $req->input("idLocal", null);
        $cliente = $this->auth->guard("web")->user();
        $passeios = $this->passeioController->getPasseiosByDataEColetividade(
                date("Y"), date("m"), null, true);

        $data = [
            "localPreSelecionado" => is_null($idLocal) ? null : Local::find($idLocal),
            "businessStartingTime" => config("general.businessTime.start"),
            "businessEndingTime" => config("general.businessTime.end"),
            "passeios" => $passeios,
            "modalidades" => Modalidade::all(),
            "locais" => $this->localController->getLocaisAtuantesParaCliente($cliente),
            "dias" => Dia::orderBy("idDia", "asc")->get()->map(function($dia) {
                        $arr = $dia->toArray();
                        $arr["nome"] = $dia->nomeFormatado;
                        return (object) $arr;
                    }),
            "caes" => $cliente->caes,
            "idModalidadeBaseColetiva" => ModalidadeIds::COLETIVO_UNITARIO
        ];
        return response()->view("passeio.agendamento", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAgendamento(Request $req) {
        $idPasseioColetivo = $req->input("idPasseioColetivo", null);

        $data = date("Y-m-d", strtotime(str_replace("/", "-", $req->input("data"))));

        $inicio = $req->input("inicio");
        $fim = $req->input("fim");

        $modalidade = Modalidade::find($req->input("modalidade"));
        $dias = Dia::whereIn("idDia", $req->input("modalidadeDias", []))->get();
        $local = Local::find($req->input("local"));
        $caes = Cao::whereIn("idCao", $req->input("caes", []))->get();

        $cliente = $this->auth->guard("web")->user();

        if (empty($caes)) {
            return $this->defaultJsonResponse(false, "Por favor, selecione ao menos 1 cão para participar dos passeios agendados.");
        }

        if (!empty($idPasseioColetivo)) {
            return $this->realizarAgendamentoParaPasseioColetivo($idPasseioColetivo, $cliente, $caes);
        } else {
            return $this->realizarAgendamentoConvencional($cliente, $modalidade, $data, $inicio, $fim, $local, $caes, $dias);
        }
    }

    public function route_postAceitarAgendamento(Request $req, $id) {
        $cliente = $this->auth->guard("web")->user();
        $agendamento = $cliente->agendamentos()->where("idAgendamento", $id)->first();
        if (is_null($agendamento)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "aceitar o agendamento"]));
        }

        $primeiroPasseio = $agendamento->passeios()->orderBy("data", "asc")->first();
        if (date("Y-m-d") >= $primeiroPasseio->data) {
            \DB::beginTransaction();
            try {
                if (!$this->cancelarAgendamento($agendamento, $cliente, "Data do primeiro passeio expirada.")) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "aceitar o agendamento"]));
                }
                \DB::commit();
                return $this->defaultJsonResponse(false, "A data do primeiro passeio programado já passou. "
                                . "Portanto, o agendamento em questão é considerado inválido e foi cancelado. "
                                . "Por favor, solicite um novo agendamento.", [
                            "messageLevel" => "warning",
                            "redirect" => true
                ]);
            } catch (\Exception $ex) {
                \DB::rollBack();
                throw $ex;
            }
        }

        \DB::beginTransaction();
        try {
            if (!$this->aceitarAgendamento($agendamento, $cliente)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postCancelarAgendamento(Request $req, $id) {
        $cliente = $this->auth->guard("web")->user();
        $motivo = $req->input("motivo", null);

        $agendamento = $cliente->agendamentos()->where("idAgendamento", $id)->first();
        if (is_null($agendamento)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "cancelar o agendamento"]), null);
        }
        \DB::beginTransaction();
        try {
            if (!$this->cancelarAgendamento($agendamento, $cliente, $motivo)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
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
    public function salvarAgendamento($id = null, $dados) {
        if (is_null($id)) {
            $agendamento = new Agendamento();
        } else {
            $agendamento = Agendamento::find($id);
        }
        if (isset($dados["idModalidade"])) {
            $agendamento->idModalidade = $dados["idModalidade"];
        }
        if (isset($dados["idCliente"])) {
            $agendamento->idCliente = $dados["idCliente"];
        }
        if (isset($dados["status"])) {
            $agendamento->status = $dados["status"];
        }
        if (isset($dados["precoPorCaoPorHora"])) {
            $agendamento->precoPorCaoPorHora = $dados["precoPorCaoPorHora"];
        }
        $agendamento->save();
        return $agendamento;
    }

    private function aceitarAgendamento($agendamento, Pessoa $pessoa) {
        if ($pessoa instanceof Cliente) {
            if ($agendamento->status !== AgendamentoStatus::CLIENTE) {
                $agendamento->putErrors(["O agendamento em questão não está pendente por parte do cliente, portanto, não pode ser aceito pelo mesmo."]);
                return false;
            }
        } elseif ($pessoa instanceof Funcionario) {
            if ($agendamento->status !== AgendamentoStatus::FUNCIONARIO) {
                $agendamento->putErrors(["O agendamento em questão não está pendente por parte do funcionário, portanto, não pode ser aceito pelo mesmo."]);
                return false;
            }
        }

        $primeiroPasseio = $agendamento->passeios()->orderBy("data", "asc")->first();
        //Se positivo, executa "cancelamento de agendamento".
        if (date("Y-m-d") >= $primeiroPasseio->data) {
            $agendamento->putErrors(["A data do primeiro passeio programado já passou. Portanto, o agendamento em questão é considerado inválido."]);
            return false;
        }

        $agendamento->status = AgendamentoStatus::FEITO;
        if (!$agendamento->save()) {
            return false;
        }

        //Inclui cães nos passeios, caso não estejam ainda, e altera o status para 'pendente'.
        foreach ($agendamento->passeios as $passeio) {
            $passeio->caes()->syncWithoutDetaching($agendamento->caes);
            $passeio->status = PasseioStatus::PENDENTE;
            if(!$passeio->save()) {
                return false;
            }
        }
        return true;
    }

    private function cancelarAgendamento($agendamento, Pessoa $pessoa, $motivo) {
        if ($agendamento->status === AgendamentoStatus::CANCELADO) {
            $agendamento->putErrors(["0 agendamento em questão já foi cancelado."]);
            return false;
        }
        if ($agendamento->passeios()->naoFeito()->count() == 0) {
            $agendamento->putErrors(["Não é possível cancelar um agendamento com todos os passeios feitos."]);
            return false;
        }
        $agendamento->status = AgendamentoStatus::CANCELADO;
        if (!$agendamento->save()) {
            return false;
        }
        $passeios = $agendamento->passeios;
        foreach ($passeios as $passeio) {
            if ($passeio->checarStatus([PasseioStatus::PENDENTE, PasseioStatus::EM_ANALISE])) {
                if (!$this->passeioController->cancelarPasseioParaAgendamento($passeio, $pessoa, $motivo, $agendamento)) {
                    $agendamento->putErrors([trans("alert.error.generic", ["message" => "cancelar o agendamento"])]);
                    return false;
                }
            }
        }
        return true;
    }

    private function realizarAgendamentoParaPasseioColetivo($idPasseioColetivo, $cliente, $caes) {
        $passeio = Passeio::find($idPasseioColetivo);
        if (is_null($passeio)) {
            return $this->defaultJsonResponse(false, "O passeio coletivo informado encontra-se indisponível. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        if ($passeio->status !== PasseioStatus::PENDENTE) {
            return $this->defaultJsonResponse(false, "Não é possível solicitar um agendamento para o passeio selecionado. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        $agendamentoEmPendencia = $passeio->agendamentos()->whereHas("cliente", function($q) use ($cliente) {
                    $q->where("idCliente", $cliente->idCliente);
                })->pendente()->first();
        if (!is_null($agendamentoEmPendencia)) {
            switch ($agendamentoEmPendencia->status) {
                case AgendamentoStatus::CLIENTE:
                    return $this->defaultJsonResponse(false, "Você já possui uma solicitação de agendamento para o passeio em questão com pendências por parte do cliente. Verifique-a na sua lista de agendamentos.");
                case AgendamentoStatus::FUNCIONARIO:
                    return $this->defaultJsonResponse(false, "Você já possui uma solicitação de agendamento para o passeio em questão sob análise. Por favor, aguarde até que ela seja avaliada.");
            }
        }
        $modalidade = Modalidade::find(ModalidadeIds::COLETIVO_UNITARIO);
        \DB::beginTransaction();
        try {
            $agendamento = $this->salvarAgendamento(null, [
                "idModalidade" => $modalidade->idModalidade,
                "idCliente" => $cliente->idCliente,
                "status" => AgendamentoStatus::FUNCIONARIO,
                "precoPorCaoPorHora" => $modalidade->precoPorCaoPorHora
            ]);
            if ($agendamento->hasErrors()) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
            }
            $porte = $passeio->porte;
            foreach ($caes as $cao) {
                if ($porte !== $cao->porte) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, "Todos os cães devem ter o mesmo porte ($porte) em um passeio coletivo.");
                }
                $agendamento->caes()->attach($cao->idCao);
            }

            $agendamento->passeios()->attach($passeio->idPasseio);
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    private function realizarAgendamentoConvencional($cliente, $modalidade, $data, $inicio, $fim, $local, $caes, $dias) {
        \DB::beginTransaction();
        try {
            $agendamento = $this->salvarAgendamento(null, [
                "idModalidade" => $modalidade->idModalidade,
                "idCliente" => $cliente->idCliente,
                "status" => AgendamentoStatus::FUNCIONARIO,
                "precoPorCaoPorHora" => $modalidade->precoPorCaoPorHora
            ]);
            if ($agendamento->hasErrors()) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $agendamento->getErrors());
            }
            $porte = $caes->first()->porte;
            foreach ($caes as $cao) {
                if ($porte !== $cao->porte && $modalidade->coletivo) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, "Todos os cães devem ter o mesmo porte em um passeio coletivo.");
                }
                $agendamento->caes()->attach($cao->idCao);
            }

            if ($modalidade->tipo === Servico::UNITARIO) {
                $passeio = $this->passeioController->salvarPasseio(null, [
                    "idLocal" => $local->idLocal,
                    "inicio" => $inicio,
                    "fim" => $fim,
                    "data" => $data,
                    "coletivo" => $modalidade->coletivo,
                    "porte" => $modalidade->coletivo ? $porte : null
                ]);
                if ($passeio->hasErrors()) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, "Ocorreu um erro ao solicitar o agendamento. Por favor, tente novamente mais tarde.");
                }
                foreach ($caes as $cao) {
                    $passeio->caes()->attach($cao->idCao);
                }
                $agendamento->passeios()->attach($passeio->idPasseio);
            } else {
                $dataAnterior = $data;
                foreach ($dias as $dia) {
                    $agendamento->dias()->attach($dia->idDia);
                }
                for ($i = 0; $i < $modalidade->quantidadeDePasseios; $i += $dias->count()) {
                    for ($j = 0; $j < $dias->count(); $j++) {
                        $dia = $dias[$j];
                        $proximaData = Carbon::parse($dataAnterior)->modify("next " . $dia->getCarbonName());
                        $passeio = $this->passeioController->salvarPasseio(null, [
                            "idLocal" => $local->idLocal,
                            "inicio" => $inicio,
                            "fim" => $fim,
                            "data" => $proximaData->format("Y-m-d"),
                            "coletivo" => $modalidade->coletivo,
                            "porte" => $modalidade->coletivo ? $porte : null
                        ]);
                        if ($passeio->hasErrors()) {
                            \DB::rollBack();
                            return $this->defaultJsonResponse(false, "Ocorreu um erro ao solicitar o agendamento. Por favor, tente novamente mais tarde.");
                        }
                        foreach ($caes as $cao) {
                            $passeio->caes()->attach($cao->idCao);
                        }
                        $agendamento->passeios()->attach($passeio->idPasseio);
                        $dataAnterior = $proximaData->format("Y-m-d");
                    }
                }
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
}
