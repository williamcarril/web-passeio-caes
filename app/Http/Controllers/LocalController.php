<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Local;
use App\Models\Eloquent\Enums\ImagemTamanho;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use App\Models\Eloquent\Cliente;
use App\Models\File\Repositorio;

class LocalController extends Controller {

    private $auth;
    private $imageController;
    private $repository;

    public function __construct(AuthFactory $auth, ImagemController $imageController, Repositorio $repository) {
        $this->auth = $auth;
        $this->imageController = $imageController;
        $this->repository = $repository;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas do passeador">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getWalkerLocais() {
        $data = [
            "locais" => Local::all()
        ];
        return response()->view("walker.local.listagem", $data);
    }

    public function route_getWalkerLocal(Request $req, $id) {
        $local = Local::findOrFail($id);
        $data = [
            "local" => $local,
            "imagens" => $local->imagens()->orderBy("ordem", "asc")->get()
        ];
        return response()->view("walker.local.detalhes", $data);
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getLocais() {
        if ($this->auth->guard("web")->check()) {
            $cliente = $this->auth->guard("web")->user();
            $locais = Local::select(\DB::raw("local.*, passeiosNoLocal.quantidade"))
                            ->leftJoin(\DB::raw("(SELECT passeio.idLocal, COUNT(passeio.idLocal) AS 'quantidade' FROM passeio 
                                INNER JOIN a_agendamento_passeio ON passeio.idPasseio = a_agendamento_passeio.idPasseio
                                INNER JOIN agendamento on a_agendamento_passeio.idAgendamento = agendamento.idAgendamento
                                WHERE agendamento.idCliente = ?
                                GROUP BY passeio.idLocal) AS passeiosNoLocal")
                                    , "passeiosNoLocal.idLocal", "=", "local.idLocal")
                            ->addBinding($cliente->idCliente, "join")
                            ->orderBy("quantidade", "desc")
                            ->orderBy("nome", "asc")->get();
            $locais = $locais->sortBy(function($local) use ($cliente) {
                $distancia = $local->distanciaEntre($cliente->lat, $cliente->lng);

                if ($distancia <= $local->raioAtuacao) {
                    return $distancia / 1000000;
                }
                return $distancia;
            });
        } else {
            $locais = Local::orderBy("nome", "asc")->get();
        }
        $data = [
            "locais" => $locais
        ];
        return response()->view("local.listagem", $data);
    }

    public function route_getLocal(Request $req, $slug) {
        $local = Local::where("slug", $slug)->firstOrFail();
        $data = [
            "local" => $local,
            "imagens" => $local->imagens()->orderBy("ordem", "asc")->get()
        ];
        return response()->view("local.detalhes", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getLocalJson(Request $req, $id) {
        $fields = explode(",", $req->input("fields", ""));

        $local = Local::find($id);
        $arr = $local->toArray();
        $arr["link"] = route("local.detalhes.get", ["slug" => $local->slug]);
        $arr["thumbnail"] = $local->thumbnail;

        if (!empty($fields)) {
            foreach ($arr as $key => $value) {
                if (!in_array($key, $fields)) {
                    unset($arr[$key]);
                }
            }
        }

        return $this->defaultJsonResponse(true, null, $arr);
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas da área administrativa">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminLocais() {
        $data = [
            "locais" => Local::withoutGlobalScopes()->get()
        ];

        return response()->view("admin.local.listagem", $data);
    }

    public function route_getAdminLocal(Request $req, $id = null) {
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
        $data["tamanhos"] = [
            "mobile" => ImagemTamanho::MOBILE_LARGURA . "x" . ImagemTamanho::MOBILE_ALTURA,
            "desktop" => ImagemTamanho::DESKTOP_LARGURA . "x" . ImagemTamanho::DESKTOP_ALTURA
        ];

        return response()->view("admin.local.salvar", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getAdminCheckNome(Request $req) {
        $nome = $req->input("nome");
        $ignore = $req->input("ignore", null);
        $check = Local::where("nome", $nome);
        if (!is_null($ignore)) {
            $check->where("nome", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getAdminCheckSlug(Request $req) {
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
    public function route_postAdminLocal(Request $req) {
        $id = $req->input("id");
        if (!is_null($id)) {
            $local = Local::find($id);
        } else {
            $local = new Local();
        }

        if ($req->has("nome")) {
            $local->nome = $req->input("nome");
        }
        if ($req->has("descricao")) {
            $local->descricao = $req->input("descricao");
        }
        if ($req->has("slug")) {
            $local->slug = $req->input("slug");
        }
        if ($req->has("raioAtuacao")) {
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
        $local->numero = $req->input("numero", null);
        $local->complemento = $req->input("complemento", null);

        \DB::beginTransaction();
        try {
            if (!$local->save()) {
                return $this->defaultJsonResponse(false, $local->getErrors());
            }
            //Salvando fotos
            $ids = $req->input("idImagem", []);
            $arquivosMobile = $req->file("imagemMobile", []);
            $arquivosDesktop = $req->file("imagemDesktop", []);

            //Descobrindo quais fotos foram removidas
            $idsAntigos = array_map(function($imagem) {
                return $imagem["idImagem"];
            }, $local->imagens->toArray());

            //Limpa as relações entre locais e fotos
            $local->imagens()->detach();

            //Deleta as imagens removidas
            $idsRemovidos = array_diff($idsAntigos, $ids);
            foreach ($idsRemovidos as $idRemovido) {
                if (!$this->imageController->deletar($idRemovido)) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, trans("alert.error.update", ["entity" => "este local de passeio"]));
                }
            }

            //Salvando/atualizando fotos
            foreach ($ids as $i => $id) {
                $imagem = $this->salvarFoto($id, $local, $arquivosMobile[$i], $arquivosDesktop[$i]);
                if ($imagem === false) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "salvar a foto do local de passeio"]));
                }
                $local->imagens()->attach($imagem->idImagem, ["ordem" => $i]);
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
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Outros métodos">
    public function getLocaisAtuantesParaCliente(Cliente $cliente) {
        $locais = Local::all();
        $locais = $locais->filter(function($local) use ($cliente) {
            return $local->verificarServico($cliente->lat, $cliente->lng);
        });
        return $locais;
    }

    private function salvarFoto($id, $local, $arquivoMobile, $arquivoDesktop) {
        $arquivos = [];
        if (!empty($arquivoMobile)) {
            $arquivoMobile = $this->repository->saveImage($arquivoMobile, ImagemTamanho::MOBILE_LARGURA, ImagemTamanho::MOBILE_ALTURA);

            if ($arquivoMobile === false) {
                return false;
            }
            $arquivos[] = ["arquivo" => $arquivoMobile, "tamanho" => ImagemTamanho::MOBILE];
        }
        if (!empty($arquivoDesktop)) {
            $arquivoDesktop = $this->repository->saveImage($arquivoDesktop, ImagemTamanho::DESKTOP_LARGURA, ImagemTamanho::DESKTOP_ALTURA);

            if ($arquivoDesktop === false) {
                return false;
            }
            $arquivos[] = ["arquivo" => $arquivoDesktop, "tamanho" => ImagemTamanho::DESKTOP];
        }
        $imagem = $this->imageController->salvar($id, $local->nome, $arquivos, false);

        if ($imagem->hasErrors()) {
            return false;
        }
        return $imagem;
    }

    // </editor-fold>
}
