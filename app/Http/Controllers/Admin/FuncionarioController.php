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
            "funcionario" => $funcionario
        ];
        return response()->view("admin.funcionario.manter", $data);
    }

    public function route_getPasseador(Request $req, $id) {
        $passeador = Funcionario::withoutGlobalScopes()
                ->passeador()
                ->where("idFuncionario", $id)
                ->first();
        if (is_null($passeador)) {
            return abort(404);
        }
        $data = [
            "funcionario" => $passeador
        ];
        return response()->view("admin.funcionario.manter", $data);
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
