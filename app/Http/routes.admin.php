<?php

Route::get("/login", ["as" => "admin.login.get", "uses" => "FuncionarioController@route_getLogin"]);
Route::post("/login", ["as" => "admin.login.post", "uses" => "FuncionarioController@route_postLogin"]);
Route::group(["middleware" => "auth.admin"], function() {
    Route::get("/", ["as" => "admin.home", "uses" => "HomeController@route_getHome"]);
    Route::get("/logout", ["as" => "admin.logout.get", "uses" => "FuncionarioController@route_getLogout"]);

    Route::group(["prefix" => "funcionario"], function() {
        Route::get("/", ["as" => "admin.funcionario.listagem.get", "uses" => "FuncionarioController@route_getFuncionarios"]);
        Route::get("{id}", ["as" => "admin.funcionario.alterar.get", "uses" => "FuncionarioController@route_getFuncionarios"]);
    });
});
