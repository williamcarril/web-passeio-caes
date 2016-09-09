<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Funcionario;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use App\Models\File\Repositorio;
use App\Http\Controllers\ImagemController;

class FuncionarioController extends Controller {

    private $auth;
    private $repository;
    private $imageController;

    public function __construct(Repositorio $repository, AuthFactory $auth, ImagemController $imageController) {
        $this->repository = $repository;
        $this->auth = $auth;
        $this->imageController = $imageController;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getLogin(Request $req) {
        if ($this->auth->guard("admin")->check()) {
            return redirect()->route("admin.home");
        }
        $data = [];
        return response()->view("admin.login", $data);
    }

    public function route_getPasseadores() {
        $data = [
            "title" => "Passeadores",
            "funcionarios" => Funcionario::withoutGlobalScopes()->passeador()->get()
        ];
        return response()->view("admin.funcionario.listagem", $data);
    }

    public function route_getFuncionario(Request $req) {
        $funcionario = $this->auth->guard("admin")->user();
        $data = [
            "funcionario" => $funcionario,
            "title" => "Alterar cadastro",
            "ignoreOnChecks" => [
                "cpf" => $funcionario->cpf,
                "rg" => $funcionario->rg,
                "email" => $funcionario->email
            ]
        ];
        return response()->view("admin.funcionario.salvar", $data);
    }

    public function route_getPasseador(Request $req, $id = null) {
        if (!is_null($id)) {
            $passeador = Funcionario::withoutGlobalScopes()
                    ->passeador()
                    ->where("idFuncionario", $id)
                    ->first();
            if (is_null($passeador)) {
                return abort(404);
            }
            $data = [
                "funcionario" => $passeador,
                "title" => "Editar passeador",
                "ignoreOnChecks" => [
                    "cpf" => $passeador->cpf,
                    "rg" => $passeador->rg,
                    "email" => $passeador->email
                ]
            ];
        } else {
            $data = [
                "title" => "Novo passeador"
            ];
        }
        return response()->view("admin.funcionario.salvar", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getCheckEmail(Request $req) {
        $email = $req->input("email");
        $ignore = $req->input("ignore", null);
        $check = Funcionario::where("email", $email);
        if (!is_null($ignore)) {
            $check->where("email", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getCheckCpf(Request $req) {
        $cpf = preg_replace('/[^0-9]/', '', $req->input("cpf"));
        $ignore = $req->input("ignore", null);
        $check = Funcionario::where("cpf", $cpf);
        if (!is_null($ignore)) {
            $check->where("cpf", "!=", $ignore);
        }
        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getCheckRg(Request $req) {
        $rg = preg_replace('/[^0-9a-zA-Z]/', '', $req->input("rg"));
        $ignore = $req->input("ignore", null);
        $check = Funcionario::where("rg", $rg);
        if (!is_null($ignore)) {
            $check->where("rg", "!=", $ignore);
        }
        return $this->defaultJsonResponse($check->exists());
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postFuncionario(Request $req) {
        $id = $req->input("id");
        if (!is_null($id)) {
            $funcionario = Funcionario::find($id);
        } else {
            $funcionario = new Funcionario();
            $funcionario->tipo = "passeador";
        }

        if ($req->input("senha") !== $req->input("senha2")) {
            return $this->defaultJsonResponse(false, "Os campos de senha devem ser iguais.");
        }

        if ($req->has("nome")) {
            $funcionario->nome = $req->input("nome");
        }
        if ($req->has("cpf")) {
            $funcionario->cpf = $req->input("cpf");
        }
        if ($req->has("rg")) {
            $funcionario->rg = $req->input("rg");
        }
        if ($req->has("telefone")) {
            $funcionario->telefone = $req->input("telefone");
        }
        if ($req->has("email")) {
            $funcionario->email = $req->input("email");
        }
        if ($req->has("senha")) {
            $funcionario->senha = $req->input("senha");
        }
        if ($req->has("lat")) {
            $funcionario->lat = $req->input("lat");
        }
        if ($req->has("lng")) {
            $funcionario->lng = $req->input("lng");
        }
        if ($req->has("postal")) {
            $funcionario->postal = $req->input("postal");
        }
        if ($req->has("logradouro")) {
            $funcionario->logradouro = $req->input("logradouro");
        }
        if ($req->has("bairro")) {
            $funcionario->bairro = $req->input("bairro");
        }
        if ($req->has("numero")) {
            $funcionario->numero = $req->input("numero");
        }
        if ($req->has("complemento")) {
            $funcionario->complemento = $req->input("complemento");
        }
        if ($req->hasFile("imagem")) {
            if (!is_null($funcionario->idImagem)) {
                //Guarda referência da imagem antiga para ser deletada posteriormente
                $idImagemAntiga = $funcionario->idImagem;
            }
            $nomeDoArquivo = $this->repository->saveImage($req->file("imagem"), 100, 100);
            if ($nomeDoArquivo === false) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "salvar a imagem do funcionário"]));
            }
            $imagem = $this->imageController->salvar(null, $funcionario->nome, null, [
                ["arquivo" => $nomeDoArquivo]
            ]);
            if ($imagem->hasErrors()) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $imagem->getErrors());
            }
            $funcionario->idImagem = $imagem->idImagem;
        }

        \DB::beginTransaction();
        try {
            if (!$funcionario->save()) {
                return $this->defaultJsonResponse(false, $funcionario->getErrors());
            }
            if (!empty($idImagemAntiga)) {
                if (!$this->imageController->deletar($idImagemAntiga)) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, trans("alert.error.update", ["entity" => "este funcionário"]));
                }
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postAlterarStatus(Request $req) {
        $id = $req->input("id");
        $passeador = Funcionario::withoutGlobalScopes()->passeador()->where("idFuncionario", $id)->first();
        if (is_null($passeador)) {
            return $this->defaultJsonResponse(false, "O passeador selecionado não foi encontrado no sistema. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        $passeador->ativo = !$passeador->ativo;
        if (!$passeador->save()) {
            return $this->defaultJsonResponse(false, $passeador->getErrors());
        }
        return $this->defaultJsonResponse(true, null, [
                    "status" => $passeador->ativoFormatado
        ]);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas que causam redirects">
    public function route_postLogin(Request $req) {
        $email = $req->input("email");
        $senha = $req->input("senha");
        $funcionario = Funcionario::administrador()->where(["email" => $email])->first();
        if (!is_null($funcionario) && \Hash::check($senha, $funcionario->senha)) {
            $this->auth->guard("admin")->login($funcionario);
            return redirect()->route("admin.home");
        }
        return redirect()->back()->withErrors(["As credenciais fornecidas estão incorretas."]);
    }

    public function route_getLogout(Request $req) {
        $this->auth->guard("admin")->logout();
        return redirect()->route("admin.login.get");
    }

// </editor-fold>
}