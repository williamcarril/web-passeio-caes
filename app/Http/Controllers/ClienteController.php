<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Cliente;
use App\Models\Eloquent\Cao;
use App\Models\Eloquent\Imagem;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use App\Models\File\Repositorio;

class ClienteController extends Controller {

    private $auth;
    private $repository;

    public function __construct(Repositorio $repository, AuthFactory $auth) {
        $this->repository = $repository;
        $this->auth = $auth;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getCadastroView(Request $req) {
        $data = [];
        return response()->view("cliente.cadastro", $data);
    }

    public function route_getCaesView(Request $req) {
        $cliente = $this->auth->guard("web")->user();
        $data = [
            "caes" => $cliente->caes()->ativo()->get()
        ];
        return response()->view("cliente.caes", $data);
    }

    public function route_getVacinacao(Request $req, $id) {
        return $id;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getCheckEmail(Request $req) {
        $email = $req->input("email");
        $cliente = $this->auth->guard("web")->user();

        $check = Cliente::where("email", $email);
        if (!is_null($cliente)) {
            $check->where("idCliente", "!=", $cliente->idCliente);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getCheckCpf(Request $req) {
        $cpf = $req->input("cpf");
        $cliente = $this->auth->guard("web")->user();

        $check = Cliente::where("cpf", $cpf);
        if (!is_null($cliente)) {
            $check->where("idCliente", "!=", $cliente->idCliente);
        }
        return $this->defaultJsonResponse($check->exists());
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postCadastro(Request $req) {
        $cliente = $this->auth->guard("web")->user();
        if (is_null($cliente)) {
            $cliente = new Cliente();
        }

        if ($req->input("senha") !== $req->input("senha2")) {
            return $this->defaultJsonResponse(false, "Os campos de senha devem ser iguais.");
        }

        if ($req->has("nome")) {
            $cliente->nome = $req->input("nome");
        }
        if ($req->has("cpf")) {
            $cliente->cpf = $req->input("cpf");
        }
        if ($req->has("telefone")) {
            $cliente->telefone = $req->input("telefone");
        }
        if ($req->has("email")) {
            $cliente->email = $req->input("email");
        }
        if ($req->has("senha")) {
            $cliente->senha = \bcrypt($req->input("senha"));
        }
        if ($req->has("lat")) {
            $cliente->lat = $req->input("lat");
        }
        if ($req->has("lng")) {
            $cliente->lng = $req->input("lng");
        }
        if ($req->has("postal")) {
            $cliente->postal = $req->input("postal");
        }
        if ($req->has("logradouro")) {
            $cliente->logradouro = $req->input("logradouro");
        }
        if ($req->has("bairro")) {
            $cliente->bairro = $req->input("bairro");
        }
        if ($req->has("numero")) {
            $cliente->numero = $req->input("numero");
        }
        if ($req->has("complemento")) {
            $cliente->complemento = $req->input("complemento");
        }

        \DB::beginTransaction();
        try {
            if (!$cliente->save()) {
                return $this->defaultJsonResponse(false, $cliente->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    /**
     * @todo Impedir alteração de regras para passeio como "Porte" e "Gênero".
     */
    public function route_postCaes(Request $req) {
        $id = $req->input("id");
        $cliente = $this->auth->guard("web")->user();
        \DB::beginTransaction();
        try {
            if (!empty($id)) {
                $cao = $cliente->caes()->where("idCao", $id)->first();
                if (is_null($cao)) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, "Não foi possível alterar este cachorro. Por favor, atualize a página ou tente novamente mais tarde.");
                }
            } else {
                $cao = new Cao();
            }
            if ($req->has("nome")) {
                $cao->nome = $req->input("nome");
            }
            if ($req->has("raca")) {
                $cao->raca = $req->input("raca");
            }
            if ($req->has("porte")) {
                $cao->porte = $req->input("porte");
            }
            if ($req->has("genero")) {
                $cao->genero = $req->input("genero");
            }
            if ($req->hasFile("imagem")) {
                $filename = $this->repository->save($req->file("imagem"));
                $imagem = new Imagem();
                $imagem->descricao = null;
                $imagem->arquivo = $filename;
                if (!$imagem->save()) {
                    \DB::rollBack();
                    return $this->defaultJsonResponse(false, $imagem->getErrors());
                }
                $cao->idImagem = $imagem->idImagem;
            }
            $cao->idCliente = $cliente->idCliente;
            if (!$cao->save()) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $cao->getErrors());
            }
            \DB::commit();
            $data = $cao->toArray();
            $data["thumbnail"] = $cao->thumbnail;
            return $this->defaultJsonResponse(true, null, $data);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postDeleteCao(Request $req) {
        $id = $req->input("id");
        $cliente = $this->auth->guard("web")->user();
        \DB::beginTransaction();
        try {
            $result = true;
            $cao = $cliente->caes()->where("idCao", $id)->first();
            if (is_null($cao)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, trans("alert.error.deletion", ["entity" => "este cachorro"]));
            }
            if ($cao->passeios()->exists()) {
                $cao->ativo = false;
                $result = $cao->save();
            } else {
                if ($cao->vacinacoes()->exists()) {
                    $result = $cao->vacinacoes()->delete();
                }
                //Guarda a referencia do arquivo para apagar caso tudo tenha corrido bem...
                $imagem = $cao->imagem;
                $result &= $cao->delete();
            }
            if (!$result) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $cao->getErrors());
            }
            //Apaga o arquivo, caso necessário
            if (!empty($imagem) && $imagem->delete()) {
                $this->repository->delete($imagem->arquivo);
            } else {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, trans("alert.error.deletion", ["entity" => "este cachorro"]));
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas que causam redirects">
    public function route_postLogin(Request $req) {
        $email = $req->input("email");
        $senha = $req->input("senha");
        $cliente = Cliente::where(["email" => $email])->first();
        if (!is_null($cliente) && \Hash::check($senha, $cliente->senha)) {
            $this->auth->guard("web")->login($cliente);
            return redirect()->back();
        }
        return redirect()->back()->withErrors(["As credenciais fornecidas estão incorretas."]);
    }

    public function route_getLogout(Request $req) {
        $this->auth->guard("web")->logout();
        return redirect()->back();
    }

    // </editor-fold>
}
