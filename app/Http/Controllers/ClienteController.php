<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eloquent\Cliente;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class ClienteController extends Controller {

    private $auth;

    public function __construct(AuthFactory $auth) {
        $this->auth = $auth;
    }

    public function route_getCadastro(Request $req) {
        $data = [];

        return response()->view("cliente.cadastro", $data);
    }

    public function route_postCadastro(Request $req) {
        if ($req->input("senha") !== $req->input("senha2")) {
            return $this->defaultJsonResponse(false, "Os campos de senha devem ser iguais.");
        }

        $cliente = new Cliente();
        $cliente->nome = $req->input("nome");
        $cliente->cpf = $req->input("cpf");
        $cliente->telefone = $req->input("telefone");
        $cliente->email = $req->input("email");
        $cliente->senha = \bcrypt($req->input("senha"));
        $cliente->lat = $req->input("lat");
        $cliente->lng = $req->input("lng");
        $cliente->postal = $req->input("postal");
        $cliente->logradouro = $req->input("logradouro");
        $cliente->bairro = $req->input("bairro");
        $cliente->numero = $req->input("numero");
        $cliente->complemento = $req->input("complemento");

        \DB::beginTransaction();
        try {
            if (!$cliente->save()) {
                return $this->defaultJsonResponse(false, $cliente->getErrors());
            }
            \DB::commit();
            return $this->defaultJsonResponse(true);
        } catch (\Exception $ex) {
            \DB::rollback();
            return $this->defaultJsonResponse(false, $ex->getMessage());
        }
    }

    public function route_getCheckEmail(Request $req) {
        $email = $req->input("email");
        return $this->defaultJsonResponse(Cliente::where("email", $email)->exists());
    }

    public function route_getCheckCpf(Request $req) {
        $cpf = $req->input("cpf");
        return $this->defaultJsonResponse(Cliente::where("cpf", $cpf)->exists());
    }

    public function route_postLogin(Request $req) {
        $email = $req->input("email");
        $senha = $req->input("senha");
        $cliente = Cliente::where(["email" => $email])->first();
        if (!is_null($cliente) && \Hash::check($senha, $cliente->senha)) {
            $this->auth->login($cliente);
            return redirect()->back();
        }
        return redirect()->back()->withErrors(["As credenciais fornecidas estão incorretas."]);
    }

    public function route_getLogout(Request $req) {
        $this->auth->logout();
        return redirect()->back();
    }

}
