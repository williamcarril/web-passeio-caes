<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Eloquent\Local;
use App\Http\Controllers\Controller;

class LocalController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getLocais() {
        $data = [
            "locais" => Local::withoutGlobalScopes()->get()
        ];

        return response()->view("admin.local.listagem", $data);
    }

    public function route_getLocal(Request $req, $id = null) {
        if (is_null($id)) {
            $data = [
                "title" => "Novo local"
            ];
        } else {
            $local = Local::withoutGlobalScopes()
                    ->where("idLocal", $id)
                    ->first();
            if (is_null($local)) {
                return abort(404);
            }
            $data = [
                "title" => "Alterar local",
                "local" => $local,
                "imagens" => $local->getImagensOrdenadas(),
                "ignoreOnChecks" => [
                    "nome" => $local->nome,
                    "slug" => $local->slug
                ]
            ];
        }

        return response()->view("admin.local.salvar", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getCheckNome(Request $req) {
        $nome = $req->input("nome");
        $ignore = $req->input("ignore", null);
        $check = Local::where("nome", $nome);
        if (!is_null($ignore)) {
            $check->where("nome", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getCheckSlug(Request $req) {
        $slug = $req->input("slug");
        $ignore = $req->input("ignore", null);
        $check = Local::where("slug", $slug);
        if (!is_null($ignore)) {
            $check->where("slug", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postLocal(Request $req) {
        $id = $req->input("id");
        if (!is_null($id)) {
            $local = Local::find($id);
        } else {
            $local = new Local();
        }

        if ($req->has("nome")) {
            $local->nome = $req->input("nome");
        }
        if($req->has("descricao")) {
            $local->descricao = $req->input("descricao");
        }
        if($req->has("slug")) {
            $local->slug = $req->input("slug");
        }
        if($req->has("raioAtuacao")) {
            $local->raioAtuacao = $req->input("raioAtuacao");
        }
        if ($req->has("lat")) {
            $local->lat = $req->input("lat");
        }
        if ($req->has("lng")) {
            $local->lng = $req->input("lng");
        }
        if ($req->has("postal")) {
            $local->postal = $req->input("postal");
        }
        if ($req->has("logradouro")) {
            $local->logradouro = $req->input("logradouro");
        }
        if ($req->has("bairro")) {
            $local->bairro = $req->input("bairro");
        }
        if ($req->has("numero")) {
            $local->numero = $req->input("numero");
        }
        if ($req->has("complemento")) {
            $local->complemento = $req->input("complemento");
        }
//        if ($req->hasFile("imagem")) {
//            if (!is_null($local->idImagem)) {
//                //Guarda referência da imagem antiga para ser deletada posteriormente
//                $idImagemAntiga = $local->idImagem;
//            }
//            $nomeDoArquivo = $this->repository->saveImage($req->file("imagem"), 100, 100);
//            if ($nomeDoArquivo === false) {
//                \DB::rollBack();
//                return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "salvar a imagem do funcionário"]));
//            }
//            $imagem = $this->imageController->salvar(null, $local->nome, null, [
//                ["arquivo" => $nomeDoArquivo]
//            ]);
//            if ($imagem->hasErrors()) {
//                \DB::rollBack();
//                return $this->defaultJsonResponse(false, $imagem->getErrors());
//            }
//            $local->idImagem = $imagem->idImagem;
//        }

        \DB::beginTransaction();
        try {
            if (!$local->save()) {
                return $this->defaultJsonResponse(false, $local->getErrors());
            }
//            if (!empty($idImagemAntiga)) {
//                if (!$this->imageController->deletar($idImagemAntiga)) {
//                    \DB::rollBack();
//                    return $this->defaultJsonResponse(false, trans("alert.error.update", ["entity" => "este funcionário"]));
//                }
//            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postAlterarStatus(Request $req) {
        $id = $req->input("id");
        $local = Local::withoutGlobalScopes()->where("idLocal", $id)->first();
        if (is_null($local)) {
            return $this->defaultJsonResponse(false, "O local selecionado não foi encontrado no sistema. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        $local->ativo = !$local->ativo;
        if (!$local->save()) {
            return $this->defaultJsonResponse(false, $local->getErrors());
        }
        return $this->defaultJsonResponse(true, null, [
                    "status" => $local->ativoFormatado
        ]);
    }

    // </editor-fold>
}
