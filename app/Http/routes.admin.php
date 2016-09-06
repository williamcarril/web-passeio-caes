<?php

Route::get("/login", ["as" => "admin.login.get", "uses" => "FuncionarioController@route_getLogin"]);
Route::post("/login", ["as" => "admin.login.post", "uses" => "FuncionarioController@route_postLogin"]);
Route::group(["middleware" => "auth.admin"], function() {
    Route::get("/", ["as" => "admin.home", "uses" => "HomeController@route_getHome"]);
    Route::get("/logout", ["as" => "admin.logout.get", "uses" => "FuncionarioController@route_getLogout"]);

    Route::group(["prefix" => "funcionario"], function() {
        Route::get("/", ["as" => "admin.funcionario.alterar.get", "uses" => "FuncionarioController@route_getFuncionario"]);
        Route::get("/passeador", ["as" => "admin.funcionario.passeador.listagem.get", "uses" => "FuncionarioController@route_getPasseadores"]);
        Route::get("/passeador/{id}", ["as" => "admin.funcionario.passeador.alterar.get", "uses" => "FuncionarioController@route_getPasseador"]);
    });
});
