<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Cliente;
use App\Models\Eloquent\Cao;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use App\Models\File\Repositorio;

class ClienteController extends Controller {

    private $auth;
    private $repository;
    private $imageController;

    public function __construct(Repositorio $repository, AuthFactory $auth, ImagemController $imageController) {
        $this->repository = $repository;
        $this->auth = $auth;
        $this->imageController = $imageController;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas administrativas">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getAdminClientes() {
        $data = [
            "clientes" => Cliente::withoutGlobalScopes()->get()
        ];
        return response()->view("admin.cliente.listagem", $data);
    }

    public function route_getAdminCliente(Request $req, $id) {
        $cliente = Cliente::findOrFail($id);
        $data = [
            "customer" => $cliente,
            "ignoreOnChecks" => [
                "cpf" => $cliente->cpf,
                "email" => $cliente->email
            ]
        ];
        return response()->view("admin.cliente.salvar", $data);
    }

    public function route_getAdminCaes(Request $req, $id) {
        $cliente = Cliente::withoutGlobalScopes()->where("idCliente", $id)->first();
        $data = [
            "caes" => $cliente->caes()->withoutGlobalScopes()->get(),
            "cliente" => $cliente
        ];
        return response()->view("admin.cliente.caes", $data);
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getAdminCheckEmail(Request $req) {
        $email = $req->input("email");
        $ignore = $req->input("ignore");

        $check = Cliente::where("email", $email);
        if (!is_null($ignore)) {
            $check->where("email", "!=", $ignore);
        }

        return $this->defaultJsonResponse($check->exists());
    }

    public function route_getAdminCheckCpf(Request $req) {
        $cpf = preg_replace('/[^0-9]/', '', $req->input("cpf"));
        $ignore = $req->input("ignore");

        $check = Cliente::where("cpf", $cpf);
        if (!is_null($ignore)) {
            $ignore = preg_replace('/[^0-9]/', '', $ignore);
            $check->where("cpf", "!=", $ignore);
        }
        return $this->defaultJsonResponse($check->exists());
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas POST que retornam JSON">
    public function route_postAdminCliente(Request $req, $id) {
        $cliente = Cliente::withoutGlobalScopes()->where("idCliente", $id)->first();
        if (is_null($cliente)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "alterar o cliente"]));
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
            $cliente->senha = $req->input("senha");
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
        $cliente->complemento = $req->input("complemento", null);

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

    public function route_postAdminAlterarStatus(Request $req) {
        $id = $req->input("id");
        $cliente = Cliente::withoutGlobalScopes()->where("idCliente", $id)->first();
        if (is_null($cliente)) {
            return $this->defaultJsonResponse(false, "O cliente selecionado não foi encontrado no sistema. Por favor, atualize a página ou tente novamente mais tarde.");
        }
        $cliente->ativo = !$cliente->ativo;
        if (!$cliente->save()) {
            return $this->defaultJsonResponse(false, $cliente->getErrors());
        }
        return $this->defaultJsonResponse(true, null, [
                    "status" => $cliente->ativoFormatado
        ]);
    }

    public function route_postAdminSalvarCao(Request $req, $idCliente) {
        $id = $req->input("id");
        $cliente = Cliente::withoutGlobalScopes()->where("idCliente", $idCliente)->first();

        \DB::beginTransaction();
        try {
            if (!empty($id)) {
                $cao = Cao::withoutGlobalScopes()->where("idCao", $id)->first();
            } else {
                $cao = null;
            }

            $dados = $req->all();
            if ($req->hasFile("imagem")) {
                $dados["imagem"] = $req->file("imagem");
            } else {
                unset($dados["imagem"]);
            }

            $cao = $this->salvarCao($cliente, $cao, $dados);
            if ($cao->hasErrors()) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $cao->getErrors());
            }
            \DB::commit();
            $data = $cao->toArray();
            $data["thumbnail"] = $cao->thumbnail;
            $data["ativo"] = $cao->ativoFormatado;
            return $this->defaultJsonResponse(true, null, $data);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postAdminAlterarStatusCao(Request $req, $idCliente) {
        $id = $req->input("id");
        $cliente = $cliente = Cliente::withoutGlobalScopes()->where("idCliente", $idCliente)->first();
        $cao = $cliente->caes()->withoutGlobalScopes()->where("idCao", $id)->first();
        if (is_null($cao)) {
            return $this->defaultJsonResponse(false, trans("alert.error.generic", ["message" => "alterar o status deste cão"]));
        }
        \DB::beginTransaction();
        try {
            if (!$this->alterarStatusDoCao($cao)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $cao->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse(true, null, [
                "status" => $cao->ativoFormatado
            ]);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getCadastroView(Request $req) {
        $data = [];
        return response()->view("cliente.cadastro", $data);
    }

    public function route_getCaesView(Request $req) {
        $cliente = $this->auth->guard("web")->user();
        $data = [
            "caes" => $cliente->caes()->get()
        ];
        return response()->view("cliente.cao.manter", $data);
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
        $cpf = preg_replace('/[^0-9]/', '', $req->input("cpf"));

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
        //Verifica se é necessário realizar login do usuário
        $logarClienteNovo = false;
        $cliente = $this->auth->guard("web")->user();
        if (is_null($cliente)) {
            $cliente = new Cliente();
            $logarClienteNovo = true;
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
            $cliente->senha = $req->input("senha");
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
        $cliente->complemento = $req->input("complemento", null);

        \DB::beginTransaction();
        try {
            if (!$cliente->save()) {
                return $this->defaultJsonResponse(false, $cliente->getErrors());
            }
            \DB::commit();
            if ($logarClienteNovo) {
                $this->auth->guard("web")->login($cliente);
            }
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_postCaes(Request $req) {
        $id = $req->input("id");
        $cliente = $this->auth->guard("web")->user();
        \DB::beginTransaction();
        try {
            if (!empty($id)) {
                $cao = $cliente->caes()->where("idCao", $id)->first();
            } else {
                $cao = null;
            }
            $dados = $req->all();
            if ($req->hasFile("imagem")) {
                $dados["imagem"] = $req->file("imagem");
            } else {
                unset($dados["imagem"]);
            }
            $cao = $this->salvarCao($cliente, $cao, $dados);
            if ($cao->hasErrors()) {
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
        $cao = $cliente->caes()->where("idCao", $id)->first();
        if (is_null($cao)) {
            return $this->defaultJsonResponse(false, trans("alert.error.deletion", ["entity" => "este cachorro"]));
        }
        \DB::beginTransaction();
        try {
            if (!$this->alterarStatusDoCao($cao, false)) {
                \DB::rollBack();
                return $this->defaultJsonResponse(false, $cao->getErrors());
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
        return redirect()->route("home");
    }

    // </editor-fold>
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Outros métodos">
    public function alterarStatusDoCao($cao, $status = null) {
        if(is_null($status)) {
            $cao->ativo = !$cao->ativo;
        } else {
            $cao->ativo = $status;
        }
        return $cao->save();
    }

    public function salvarCao($cliente, $cao = null, $dados = []) {
        if (empty($cao)) {
            $cao = new Cao();
        }

        if (isset($dados["nome"])) {
            $cao->nome = $dados["nome"];
        }
        if (isset($dados["raca"])) {
            $cao->raca = $dados["raca"];
        }
        if (isset($dados["porte"])) {
            $cao->porte = $dados["porte"];
        }
        if (isset($dados["genero"])) {
            $cao->genero = $dados["genero"];
        }
        if (isset($dados["imagem"])) {
            if (!is_null($cao->idImagem)) {
                //Guarda referência da imagem antiga para ser deletada posteriormente
                $idImagemAntiga = $cao->idImagem;
            }
            $nomeDoArquivo = $this->repository->saveImage($dados["imagem"], 100, 100);
            if ($nomeDoArquivo === false) {
                $cao->putErrors([trans("alert.error.generic", ["message" => "salvar a imagem do cachorro"])]);
                return $cao;
            }
            $imagem = $this->imageController->salvar(null, $cao->nome, [
                ["arquivo" => $nomeDoArquivo]
            ]);
            if ($imagem->hasErrors()) {
                $cao->putErrors($imagem->getErrors()->all());
                return $cao;
            }
            $cao->idImagem = $imagem->idImagem;
        }
        $cao->idCliente = $cliente->idCliente;
        if (!$cao->save()) {
            return $cao;
        }
        if (!empty($idImagemAntiga)) {
            $this->imageController->deletar($idImagemAntiga);
        }
        return $cao;
    }

    public function salvarCliente($cliente, $dados = []) {
        if ($req->input("senha") !== $req->input("senha2")) {
            return $this->defaultJsonResponse(false, "Os campos de senha devem ser iguais.");
        }

        if (isset($dados["nome"])) {
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
            $cliente->senha = $req->input("senha");
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
        $cliente->complemento = $req->input("complemento", null);

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

    // </editor-fold>
}
