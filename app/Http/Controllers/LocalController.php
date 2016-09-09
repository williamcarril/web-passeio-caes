<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Local;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class LocalController extends Controller {

    private $auth;

    public function __construct(AuthFactory $auth) {
        $this->auth = $auth;
    }

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getLocais() {
        if ($this->auth->guard("web")->check()) {
            $cliente = $this->auth->guard("web")->user();
            $locais = Local::select(\DB::raw("local.*, passeiosNoLocal.quantidade"))
                    ->leftJoin(\DB::raw("(SELECT passeio.idLocal, COUNT(passeio.idLocal) AS 'quantidade' FROM passeio 
                                INNER JOIN agendamento ON passeio.idAgendamento = agendamento.idAgendamento
                                WHERE agendamento.idCliente = ?
                                GROUP BY passeio.idLocal) AS passeiosNoLocal")
                            , "passeiosNoLocal.idLocal", "=", "local.idLocal")
                    ->addBinding($cliente->idCliente, "join")
                    ->orderBy("quantidade", "desc")
                    ->orderBy("nome", "asc")->get();
            $locais->sortBy(function($local) use ($cliente) {
                return $local->distanciaEntre($cliente->lat, $cliente->lng);
            });
        } else {
            $locais = Local::orderBy("nome", "asc")->get();
        }
        $data = [
            "locais" => $locais
        ];
        return response()->view("local.listagem", $data);
    }

    public function route_getLocal($slug) {
        $local = Local::where("slug", $slug)->firstOrFail();
        $data = [
            "local" => $local,
            "imagens" => $local->imagens()->orderBy("ordem", "asc")->get()
        ];
        return response()->view("local.detalhes", $data);
    }

    // </editor-fold>
}
