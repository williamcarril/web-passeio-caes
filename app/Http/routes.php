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
                $researcher = \App::make(App\Models\Address\CepResearcher::class);
                $return = $researcher->researchAddress("03286-220");
                return $return;
            }]);
    });
}


Route::group([], function() {
    Route::get('/', ["as" => "home", "uses" => "HomeController@route_getHome"]);

    Route::group(["prefix" => "cliente"], function() {
        Route::get("/cadastro", ["as" => "cliente.cadastro.get", "uses" => "ClienteController@route_getCadastro"]);
        Route::post("/login", ["as" => "cliente.login.post", "uses" => "ClienteController@route_postLogin"]);
    });
});

Route::group(["prefix" => "api"], function() {
    Route::group(["prefix" => "v1"], function() {
        Route::resource("modalidade", "ModalidadeController");
        Route::resource("vacina", "VacinaController");
        Route::resource("trajeto", "TrajetoController");
        Route::resource("multimidia", "MultimidiaController");
    });
});

Route::group(["prefix" => "webservice"], function() {
    Route::get("cep", ["as" => "webservice.cep", "uses" => "WebserviceController@route_getAddressByCep"]);
});
