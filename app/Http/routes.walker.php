<?php

Route::get("/login", ["as" => "walker.login.get", "uses" => "FuncionarioController@route_getWalkerLogin"]);
Route::post("/login", ["as" => "walker.login.post", "uses" => "FuncionarioController@route_postWalkerLogin"]);
Route::group(["middleware" => "auth.walker"], function() {
    Route::get("/", ["as" => "walker.home", "uses" => "HomeController@route_getWalkerHome"]);
    Route::get("/logout", ["as" => "walker.logout.get", "uses" => "FuncionarioController@route_getWalkerLogout"]);

    Route::group(["prefix" => "passeio"], function() {
        Route::get("/", ["as" => "walker.passeio.confirmado.listagem.get", "uses" => "PasseioController@route_getWalkerPasseiosConfirmados"]);
        Route::get("/{id}", ["as" => "walker.passeio.detalhes.get", "uses" => "PasseioController@route_getWalkerPasseio"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/cancelar", ["as" => "walker.passeio.cancelar.post", "uses" => "PasseioController@route_postWalkerCancelarPasseio"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/iniciar", ["as" => "walker.passeio.iniciar.post", "uses" => "PasseioController@route_postWalkerIniciarPasseio"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/finalizar", ["as" => "walker.passeio.finalizar.post", "uses" => "PasseioController@route_postWalkerFinalizarPasseio"])
                ->where('id', '[0-9]+');
    });
});
