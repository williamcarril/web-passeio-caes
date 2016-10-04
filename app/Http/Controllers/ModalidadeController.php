<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Modalidade;
use App\Models\Eloquent\Enums\Periodo;
use App\Models\Eloquent\Enums\Frequencia;
use App\Models\Eloquent\Enums\Servico;

class ModalidadeController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getModalidadeJson(Request $req, $id) {
        $modalidade = Modalidade::find($id);
        $arr = $modalidade->toArray();
        $arr["tipo"] = $modalidade->tipoFormatado;
        $arr["periodo"] = $modalidade->periodoFormatado;
        $arr["frequencia"] = $modalidade->frequenciaFormatada;
        $arr["pacote"] = $modalidade->tipo === "pacote" ? true : false;
        return $this->defaultJsonResponse(true, null, $arr);
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas da área administrativa">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminModalidades() {
        $data = [
            "modalidades" => Modalidade::withoutGlobalScopes()->get()
        ];

        return response()->view("admin.modalidade.listagem", $data);
    }

    public function route_getAdminModalidade(Request $req, $id = null) {
        if (is_null($id)) {
            $data = [
                "title" => "Nova modalidade"
            ];
        } else {
            $modalidade = Modalidade::withoutGlobalScopes()
                    ->where("idModalidade", $id)
                    ->first();
            if (is_null($modalidade)) {
                return abort(404);
            }
            $data = [
                "title" => $modalidade->eModalidadeBase() ? "Alterar modalidade base" : "Alterar modalidade",
                "modalidade" => $modalidade,
                "ignoreOnChecks" => [
                    "nome" => $modalidade->nome
                ]
            ];
        }

        $data["tipos"] = [];
        foreach (Servico::getConstants() as $servico) {
            $data["tipos"][] = ["value" => $servico, "text" => Servico::format($servico)];
        }
        $data["periodos"] = [];
        foreach (Periodo::getConstants() as $periodo) {
            $data["periodos"][] = ["value" => $periodo, "text" => Periodo::format($periodo)];
        }
        $data["frequencias"] = [];
        foreach (Frequencia::getConstants() as $frequencia) {
            $data["frequencias"][] = ["value" => $frequencia, "text" => Frequencia::format($frequencia)];
        }
        return response()->view("admin.modalidade.salvar", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getAdminCheckNome(Request $req) {
        $nome = $req->input("nome");
        $ignore = $req->input("ignore", null);
        $check = Modalidade::where("nome", $nome);
        if (!is_null($ignore)) {
            $check->where("nome", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminModalidade(Request $req) {
        $id = $req->input("id");
        if (!is_null($id)) {
            $modalidade = Modalidade::find($id);
        } else {
            $modalidade = new Modalidade();
        }

        if ($req->has("nome")) {
            $modalidade->nome = $req->input("nome");
        }
        if ($req->has("descricao")) {
            $modalidade->descricao = $req->input("descricao");
        }
        if ($req->has("tipo")) {
            $modalidade->tipo = $req->input("tipo");
        }
        if ($req->has("precoPorCaoPorHora")) {
            $modalidade->precoPorCaoPorHora = $req->input("precoPorCaoPorHora");
        }
        $modalidade->coletivo = filter_var($req->input("coletivo", false), FILTER_VALIDATE_BOOLEAN);
        if ($req->has("frequencia") && $modalidade->tipo === "pacote") {
            $modalidade->frequencia = $req->input("frequencia");
        } else {
            $modalidade->frequencia = null;
        }
        if ($req->has("periodo") && $modalidade->tipo === "pacote") {
            $modalidade->periodo = $req->input("periodo");
        } else {
            $modalidade->periodo = null;
        }
        \DB::beginTransaction();
        try {
            if (!$modalidade->save()) {
                return $this->defaultJsonResponse(false, $modalidade->getErrors());
            }

            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postAdminAlterarStatus(Request $req) {
        $id = $req->input("id");
        $modalidade = Modalidade::withoutGlobalScopes()->where("idModalidade", $id)->first();
        if (is_null($modalidade)) {
            return $this->defaultJsonResponse(false, "A modalidade selecionada não foi encontrada no sistema. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        $modalidade->ativo = !$modalidade->ativo;
        if (!$modalidade->save()) {
            return $this->defaultJsonResponse(false, $modalidade->getErrors());
        }
        return $this->defaultJsonResponse(true, null, [
                    "status" => $modalidade->ativoFormatado
        ]);
    }

    // </editor-fold>
    // </editor-fold>
}
