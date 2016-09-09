<?php

Route::get("/login", ["as" => "admin.login.get", "uses" => "FuncionarioController@route_getLogin"]);
Route::post("/login", ["as" => "admin.login.post", "uses" => "FuncionarioController@route_postLogin"]);
Route::group(["middleware" => "auth.admin"], function() {
    Route::get("/", ["as" => "admin.home", "uses" => "HomeController@route_getHome"]);
    Route::get("/logout", ["as" => "admin.logout.get", "uses" => "FuncionarioController@route_getLogout"]);

    Route::group(["prefix" => "funcionario"], function() {
        Route::get("/", ["as" => "admin.funcionario.salvar.get", "uses" => "FuncionarioController@route_getFuncionario"]);
        Route::post("/salvar", ["as" => "admin.funcionario.salvar.post", "uses" => "FuncionarioController@route_postFuncionario"]);
        Route::get("/passeadores", ["as" => "admin.funcionario.passeador.listagem.get", "uses" => "FuncionarioController@route_getPasseadores"]);
        Route::get("/passeador/novo", ["as" => "admin.funcionario.passeador.novo.get", "uses" => "FuncionarioController@route_getPasseador"]);
        Route::get("/passeador/{id}", ["as" => "admin.funcionario.passeador.alterar.get", "uses" => "FuncionarioController@route_getPasseador"])
                ->where('id', '[0-9]+');
        Route::post("/passeador/alterarStatus", ["as" => "admin.funcionario.passeador.status.post", "uses" => "FuncionarioController@route_postAlterarStatus"]);
        Route::get("/check/cpf", ["as" => "admin.funcionario.check.cpf.get", "uses" => "FuncionarioController@route_getCheckCpf"]);
        Route::get("/check/email", ["as" => "admin.funcionario.check.email.get", "uses" => "FuncionarioController@route_getCheckEmail"]);
        Route::get("/check/rg", ["as" => "admin.funcionario.check.rg.get", "uses" => "FuncionarioController@route_getCheckRg"]);
    });

    Route::group(["prefix" => "locais"], function() {
        Route::get("/", ["as" => "admin.local.listagem.get", "uses" => "LocalController@route_getLocais"]);
        Route::post("/alterarStatus", ["as" => "admin.local.status.post", "uses" => "LocalController@route_postAlterarStatus"]);
        Route::get("/novo", ["as" => "admin.local.novo.get", "uses" => "LocalController@route_getLocal"]);
        Route::get("/{id}", ["as" => "admin.local.alterar.get", "uses" => "LocalController@route_getLocal"])
                ->where('id', '[0-9]+');
        Route::post("/salvar", ["as" => "admin.local.salvar.post", "uses" => "LocalController@route_postLocal"]);
        Route::get("/check/nome", ["as" => "admin.local.check.nome.get", "uses" => "LocalController@route_getCheckNome"]);
    });
});
