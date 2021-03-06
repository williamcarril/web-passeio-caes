<?php

Route::get("/login", ["as" => "admin.login.get", "uses" => "FuncionarioController@route_getAdminLogin"]);
Route::post("/login", ["as" => "admin.login.post", "uses" => "FuncionarioController@route_postAdminLogin"]);
Route::group(["middleware" => "auth.admin"], function() {
    Route::get("/", ["as" => "admin.home", "uses" => "HomeController@route_getAdminHome"]);
    Route::get("/logout", ["as" => "admin.logout.get", "uses" => "FuncionarioController@route_getAdminLogout"]);

    Route::group(["prefix" => "funcionario"], function() {
        Route::get("/", ["as" => "admin.funcionario.salvar.get", "uses" => "FuncionarioController@route_getAdminFuncionario"]);
        Route::post("/salvar", ["as" => "admin.funcionario.salvar.post", "uses" => "FuncionarioController@route_postAdminFuncionario"]);
        Route::get("/passeadores", ["as" => "admin.funcionario.passeador.listagem.get", "uses" => "FuncionarioController@route_getAdminPasseadores"]);
        Route::get("/passeador/novo", ["as" => "admin.funcionario.passeador.novo.get", "uses" => "FuncionarioController@route_getAdminPasseador"]);
        Route::get("/passeador/{id}", ["as" => "admin.funcionario.passeador.alterar.get", "uses" => "FuncionarioController@route_getAdminPasseador"])
                ->where('id', '[0-9]+');
        Route::post("/passeador/alterarStatus", ["as" => "admin.funcionario.passeador.status.post", "uses" => "FuncionarioController@route_postAdminAlterarStatus"]);
        Route::get("/check/cpf", ["as" => "admin.funcionario.check.cpf.get", "uses" => "FuncionarioController@route_getAdminCheckCpf"]);
        Route::get("/check/email", ["as" => "admin.funcionario.check.email.get", "uses" => "FuncionarioController@route_getAdminCheckEmail"]);
        Route::get("/check/rg", ["as" => "admin.funcionario.check.rg.get", "uses" => "FuncionarioController@route_getAdminCheckRg"]);
    });

    Route::group(["prefix" => "locais"], function() {
        Route::get("/", ["as" => "admin.local.listagem.get", "uses" => "LocalController@route_getAdminLocais"]);
        Route::post("/alterarStatus", ["as" => "admin.local.status.post", "uses" => "LocalController@route_postAdminAlterarStatus"]);
        Route::get("/novo", ["as" => "admin.local.novo.get", "uses" => "LocalController@route_getAdminLocal"]);
        Route::get("/{id}", ["as" => "admin.local.alterar.get", "uses" => "LocalController@route_getAdminLocal"])
                ->where('id', '[0-9]+');
        Route::post("/salvar", ["as" => "admin.local.salvar.post", "uses" => "LocalController@route_postAdminLocal"]);
        Route::get("/check/nome", ["as" => "admin.local.check.nome.get", "uses" => "LocalController@route_getAdminCheckNome"]);
        Route::get("/check/slug", ["as" => "admin.local.check.slug.get", "uses" => "LocalController@route_getAdminCheckSlug"]);
    });

    Route::group(["prefix" => "modalidade"], function() {
        Route::get("/", ["as" => "admin.modalidade.listagem.get", "uses" => "ModalidadeController@route_getAdminModalidades"]);
        Route::post("/alterarStatus", ["as" => "admin.modalidade.status.post", "uses" => "ModalidadeController@route_postAdminAlterarStatus"]);
        Route::get("/novo", ["as" => "admin.modalidade.novo.get", "uses" => "ModalidadeController@route_getAdminModalidade"]);
        Route::get("/{id}", ["as" => "admin.modalidade.alterar.get", "uses" => "ModalidadeController@route_getAdminModalidade"])
                ->where('id', '[0-9]+');
        Route::post("/salvar", ["as" => "admin.modalidade.salvar.post", "uses" => "ModalidadeController@route_postAdminModalidade"]);
        Route::get("/check/nome", ["as" => "admin.modalidade.check.nome.get", "uses" => "ModalidadeController@route_getAdminCheckNome"]);
    });

    Route::group(["prefix" => "agendamento"], function() {
        Route::get("/", ["as" => "admin.agendamento.listagem.get", "uses" => "AgendamentoController@route_getAdminAgendamentos"]);
        Route::get("/{id}", ["as" => "admin.agendamento.detalhes.get", "uses" => "AgendamentoController@route_getAdminAgendamento"])
                ->where('id', '[0-9]+');
        Route::get("/{id}/reagendar", ["as" => "admin.agendamento.reagendar.get", "uses" => "AgendamentoController@route_getAdminReagendamento"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/reagendar", ["as" => "admin.agendamento.reagendar.post", "uses" => "AgendamentoController@route_postAdminReagendamento"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/cancelar", ["as" => "admin.agendamento.cancelar.post", "uses" => "AgendamentoController@route_postAdminCancelarAgendamento"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/aceitar", ["as" => "admin.agendamento.aceitar.post", "uses" => "AgendamentoController@route_postAdminAceitarAgendamento"])
                ->where('id', '[0-9]+');
    });

    Route::group(["prefix" => "passeio"], function() {
        Route::get("/", ["as" => "admin.passeio.marcados.listagem.get", "uses" => "PasseioController@route_getAdminPasseiosMarcados"]);
        Route::get("/{id}", ["as" => "admin.passeio.detalhes.get", "uses" => "PasseioController@route_getAdminPasseio"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/alocarPasseador", ["as" => "admin.passeio.alocar.passeador.post", "uses" => "PasseioController@route_postAdminAlocarPasseador"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/status/feito", ["as" => "admin.passeio.status.feito.post", "uses" => "PasseioController@route_postAdminMarcarComoFeito"])
                ->where('id', '[0-9]+');
        Route::post("/{id}/cancelar", ["as" => "admin.passeio.cancelar.post", "uses" => "PasseioController@route_postAdminCancelarPasseio"])
                ->where('id', '[0-9]+');
    });

    Route::group(["prefix" => "cancelamento"], function() {
        Route::get("/", ["as" => "admin.cancelamento.listagem.get", "uses" => "CancelamentoController@route_getAdminCancelamentos"]);
        Route::get("/{id}", ["as" => "admin.cancelamento.detalhes.get", "uses" => "CancelamentoController@route_getAdminCancelamento"])
                ->where("id", "[0-9]+");
        Route::post("/{id}/visto", ["as" => "admin.cancelamento.visto.post", "uses" => "CancelamentoController@route_postAdminMarcarVisto"]);
    });

    Route::group(["prefix" => "cliente"], function() {
        Route::get("/", ["as" => "admin.cliente.listagem.get", "uses" => "ClienteController@route_getAdminClientes"]);
        Route::get("/{id}/alterar", ["as" => "admin.cliente.alterar.get", "uses" => "ClienteController@route_getAdminCliente"])
                ->where("id", "[0-9]+");
        Route::post("/{id}/alterar", ["as" => "admin.cliente.alterar.post", "uses" => "ClienteController@route_postAdminCliente"])
                ->where("id", "[0-9]+");
        
        Route::get("/check/email", ["as" => "admin.cliente.check.email.get", "uses" => "ClienteController@route_getAdminCheckEmail"]);
        Route::get("/check/cpf", ["as" => "admin.cliente.check.cpf.get", "uses" => "ClienteController@route_getAdminCheckCpf"]);
        Route::post("/alterarStatus", ["as" => "admin.cliente.status.post", "uses" => "ClienteController@route_postAdminAlterarStatus"]);

        Route::get("/{id}/caes", ["as" => "admin.cliente.caes.manter.get", "uses" => "ClienteController@route_getAdminCaes"])
                ->where("id", "[0-9]+");
        Route::post("/{id}/caes/alterarStatus", ["as" => "admin.cliente.caes.status.post", "uses" => "ClienteController@route_postAdminAlterarStatusCao"])
                ->where("id", "[0-9]+");
        Route::post("/{id}/caes/salvar", ["as" => "admin.cliente.caes.salvar.post", "uses" => "ClienteController@route_postAdminSalvarCao"])
                ->where("id", "[0-9]+");
    });
});
