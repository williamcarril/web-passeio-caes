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
            return \Session::all();
            }]);
    });
}


Route::group([], function() {
    Route::get('/', ["as" => "home", "uses" => "HomeController@route_getHome"]);

    Route::group(["prefix" => "cliente"], function() {
        Route::get("/cadastro", ["as" => "cliente.cadastro.get", "uses" => "ClienteController@route_getCadastro"]);
        Route::post("/cadastro", ["as" => "cliente.cadastro.post", "uses" => "ClienteController@route_postCadastro"]);
        Route::get("/cadastro/checkEmail", ["as" => "cliente.cadastro.check.email", "uses" => "ClienteController@route_getCheckEmail"]);
        Route::get("/cadastro/checkCpf", ["as" => "cliente.cadastro.check.cpf", "uses" => "ClienteController@route_getCheckCpf"]);
        Route::post("/login", ["as" => "cliente.auth.login", "uses" => "ClienteController@route_postLogin"]);
        Route::get("/logout", ["as" => "cliente.auth.logout", "uses" => "ClienteController@route_getLogout"]);
    });
});

Route::group(["prefix" => "api", "namespace" => "Api"], function() {
    Route::group(["prefix" => "v1"], function() {
        Route::resource("modalidades", "ModalidadeController");
        Route::resource("vacinas", "VacinaController");
        Route::resource("trajetos", "TrajetoController");
        Route::resource("multimidias", "MultimidiaController");
    });
});

Route::group(["prefix" => "webservice"], function() {
    Route::get("cep", ["as" => "webservice.cep", "uses" => "WebserviceController@route_getAddressByCep"]);
});
