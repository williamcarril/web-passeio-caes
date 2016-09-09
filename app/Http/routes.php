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
            $value = 1;
            $teste = increment($value);
            return $value. " " . $teste;
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
    });
});

Route::group(["prefix" => "local"], function() {
    Route::get("/", ["as" => "local.listagem.get", "uses" => "LocalController@route_getLocais"]);
    Route::get("/{slug}", ["as" => "local.detalhes.get", "uses" => "LocalController@route_getLocal"]);
});

Route::group(["prefix" => "passeio"], function() {
    Route::get("/agenda", ["as" => "passeio.agenda.get", "uses" => "PasseioController@route_getAgenda"]);
    Route::group(["middleware" => "auth.customer"], function() {
        Route::get("/", ["as" => "passeio.listagem.get", "uses" => "PasseioController@route_getPasseios"]);
        Route::get("/{id}", ["as" => "passeio.detalhes.get", "uses" => "PasseioController@route_getPasseio"]);
    });
});

Route::group(["prefix" => "api", "namespace" => "Api"], function() {
    Route::group(["prefix" => "v1"], function() {
    });
});

Route::group(["prefix" => "webservice"], function() {
    Route::get("cep", ["as" => "webservice.cep", "uses" => "WebserviceController@route_getAddressByCep"]);
});
