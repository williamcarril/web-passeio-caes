<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
if (!\App::environment("production")) {
    Route::group(["prefix" => "tests"], function() {
        Route::get("/", ["as" => "test", "uses" => function() {
            
        }]);
    });
}


Route::get('/', ["as" => "home", "uses" => "HomeController@route_getHome"]);

Route::group(["prefix" => "cliente"], function() {
    Route::get("/cadastro", ["as" => "cliente.cadastro.get", "uses" => "ClienteController@route_getCadastroView"]);
    Route::post("/cadastro", ["as" => "cliente.cadastro.post", "uses" => "ClienteController@route_postCadastro"]);
    Route::get("/cadastro/checkEmail", ["as" => "cliente.cadastro.check.email.get", "uses" => "ClienteController@route_getCheckEmail"]);
    Route::get("/cadastro/checkCpf", ["as" => "cliente.cadastro.check.cpf.get", "uses" => "ClienteController@route_getCheckCpf"]);
    Route::post("/login", ["as" => "cliente.auth.login.post", "uses" => "ClienteController@route_postLogin"]);
    Route::get("/logout", ["as" => "cliente.auth.logout.get", "uses" => "ClienteController@route_getLogout"]);

    Route::group(["middleware" => "auth.customer"], function() {
        Route::get("/cachorros", ["as" => "cliente.caes.get", "uses" => "ClienteController@route_getCaesView"]);
        Route::post("/cachorro", ["as" => "cliente.caes.post", "uses" => "ClienteController@route_postCaes"]);
        Route::post("/cachorro/delete", ["as" => "cliente.caes.delete.post", "uses" => "ClienteController@route_postDeleteCao"]);
        Route::get("/cachorro/{id}/vacinas", ["as" => "cliente.caes.vacina.get", "uses" => "ClienteController@route_getVacinacao"]);

        Route::group(["prefix" => "agendamento"], function() {
            Route::get("/", ["as" => "cliente.agendamento.get", "uses" => "AgendamentoController@route_getAgendamentosDoCliente"]);
            Route::get("/{id}", ["as" => "cliente.agendamento.detalhes.get", "uses" => "AgendamentoController@route_getAgendamentoDoCliente"])
                    ->where('id', '[0-9]+');
            Route::post("/{id}/cancelar", ["as" => "cliente.agendamento.cancelar.post", "uses" => "AgendamentoController@route_postCancelarAgendamento"])
                    ->where('id', '[0-9]+');
            Route::post("/{id}/aceitar", ["as" => "cliente.agendamento.aceitar.post", "uses" => "AgendamentoController@route_postAceitarAgendamento"])
                    ->where('id', '[0-9]+');
        });
        
        Route::group(["prefix" => "passeio"], function() {
            Route::get("/", ["as" => "cliente.passeio.confirmado.get", "uses" => "PasseioController@route_getPasseiosConfirmadosDoCliente"]);
            Route::get("/{id}", ["as" => "cliente.passeio.detalhes.get", "uses" => "PasseioController@route_getPasseioDoCliente"])
                    ->where('id', '[0-9]+');
            Route::post("/{id}/cancelar", ["as" => "cliente.passeio.cancelar.post", "uses" => "PasseioController@route_postCancelarPasseio"]);
        });
    });
});

Route::group(["prefix" => "local"], function() {
    Route::get("/", ["as" => "local.listagem.get", "uses" => "LocalController@route_getLocais"]);
    Route::get("/{slug}", ["as" => "local.detalhes.get", "uses" => "LocalController@route_getLocal"]);
    Route::get("/{id}/json", ["as" => "local.json.get", "uses" => "LocalController@route_getLocalJson"])
            ->where('id', '[0-9]+');
});

Route::group(["prefix" => "agendamento"], function() {
    Route::group(["middleware" => "auth.customer"], function() {
        Route::get("/", ["as" => "passeio.agendamento.get", "uses" => "AgendamentoController@route_getCadastrarAgendamento"]);
        Route::post("/", ["as" => "passeio.agendamento.post", "uses" => "AgendamentoController@route_postCadastrarAgendamento"]);
    });
});

Route::group(["prefix" => "passeio"], function() {
    Route::get("/ano/{ano}/mes/{mes?}/dia/{dia?}", ["as" => "passeio.porData.json.get", "uses" => "PasseioController@route_getPasseiosJson"]);
    Route::group(["middleware" => "auth.customer"], function() {
        Route::get("/{id}/json", ["as" => "passeio.json.get", "uses" => "PasseioController@route_getPasseioJson"])
                ->where('id', '[0-9]+');
    });
});

Route::group(["prefix" => "modalidade"], function() {
    Route::get("/{id}/json", ["as" => "modalidade.json.get", "uses" => "ModalidadeController@route_getModalidadeJson"]);
});

Route::group(["prefix" => "api", "namespace" => "Api"], function() {
    Route::group(["prefix" => "v1"], function() {
        
    });
});

Route::group(["prefix" => "webservice"], function() {
    Route::get("cep", ["as" => "webservice.cep", "uses" => "WebserviceController@route_getAddressByCep"]);
});
