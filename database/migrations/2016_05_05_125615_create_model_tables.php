<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::create("imagem", function($table) {
            $table->increments("idImagem");
            $table->string("nome", 75)->nullable();
            $table->date("data");
        });

        \Schema::create("imagem_arquivo", function($table) {
            $table->increments("idImagemArquivo");
            $table->integer("idImagem")->unsigned();
            $table->foreign("idImagem")->references("idImagem")->on("imagem");
            $table->enum("tamanho", ["desktop", "mobile"])->nullable();
            $table->string("arquivo", 255)->unique();
            $table->unique(["idImagem", "tamanho"]);
        });

        \Schema::create("local", function($table) {
            $table->increments("idLocal");
            $table->string("nome", 70)->unique();
            $table->text("descricao");
            $table->integer("raioAtuacao")->unsigned();
            $table->boolean("ativo")->default(true);
            $table->string("logradouro", 70);
            $table->string("bairro", 40);
            $table->char("postal", 8);
            $table->string("numero", 12)->nullable();
            $table->decimal("lat", 10, 6);
            $table->decimal("lng", 10, 6);
            $table->string("complemento", 50)->nullable();
            $table->string("slug", 70)->unique();
        });

        \Schema::create("modalidade", function($table) {
            $table->increments("idModalidade");
            $table->string("nome", 35)->unique();
            $table->text("descricao");
            $table->enum("tipo", ["unitario", "pacote"]);
            $table->enum("periodo", ["mensal", "bimestral", "trimestral", "semestral", "anual"])->nullable();
            $table->enum("frequencia", ["semanal", "bisemanal"])->nullable();
            $table->boolean("ativo")->default(true);
            $table->boolean("coletivo")->default(false);
            $table->decimal("precoPorCaoPorHora", 6, 2)->unsigned();
        });

        \Schema::create("dia", function($table) {
            $table->increments("idDia");
            $table->string("nome", 13)->unique();
        });

        \Schema::create("vacina", function($table) {
            $table->increments("idVacina");
            $table->string("nome", 30)->unique();
        });

        \Schema::create("cliente", function($table) {
            $table->increments("idCliente");
            $table->string("nome", 70);
            $table->string("telefone", 11);
            $table->boolean("ativo")->default(true);
            $table->char("cpf", 11)->unique();
            $table->string("logradouro", 70);
            $table->string("bairro", 40);
            $table->char("postal", 8);
            $table->string("numero", 12);
            $table->decimal("lat", 10, 6);
            $table->decimal("lng", 10, 6);
            $table->string("email", 80)->unique();
            $table->string("senha", 60);
            $table->string("complemento", 50)->nullable();
            $table->rememberToken();
        });
        \Schema::create("funcionario", function($table) {
            $table->increments("idFuncionario");
            $table->string("nome", 70);
            $table->string("telefone", 11);
            $table->boolean("ativo")->default(true);
            $table->char("cpf", 11)->unique();
            $table->string("logradouro", 70);
            $table->string("bairro", 40);
            $table->char("postal", 8);
            $table->string("numero", 12);
            $table->decimal("lat", 10, 6);
            $table->decimal("lng", 10, 6);
            $table->string("rg", 15)->unique();
            $table->enum("tipo", ["passeador", "administrador"]);
            $table->string("email", 80)->unique();
            $table->string("senha", 60);
            $table->string("complemento", 50)->nullable();
            $table->integer("idImagem")->unsigned();
            $table->foreign("idImagem")->references("idImagem")->on("imagem");
            $table->rememberToken();
        });

        \Schema::create("horario", function($table) {
            $table->increments("idHorario");
            $table->time("inicio")->default("00:00:00");
            $table->time("fim")->default("23:59:59");
            $table->integer("idCliente")->unsigned();
            $table->foreign("idCliente")->references("idCliente")->on("cliente");
        });

        \Schema::create("cao", function($table) {
            $table->increments("idCao");
            $table->string("nome", 50);
            $table->string("raca", 25);
            $table->enum("porte", ["pequeno", "medio", "grande"]);
            $table->enum("genero", ["macho", "femea"]);
            $table->boolean("ativo")->default(true);
            $table->integer("idCliente")->unsigned();
            $table->foreign("idCliente")->references("idCliente")->on("cliente");
            $table->integer("idImagem")->unsigned()->nullable();
            $table->foreign("idImagem")->references("idImagem")->on("imagem");
        });

        \Schema::create("vacinacao", function($table) {
            $table->increments("idVacinacao");
            $table->integer("idCao")->unsigned();
            $table->foreign("idCao")->references("idCao")->on("cao");
            $table->integer("idVacina")->unsigned();
            $table->foreign("idVacina")->references("idVacina")->on("vacina");
            $table->date("aplicacao");
            $table->date("proximaAplicacao")->nullable();
        });

        \Schema::create("agendamento", function($table) {
            $table->increments("idAgendamento");
            $table->integer("idModalidade")->unsigned();
            $table->foreign("idModalidade")->references("idModalidade")->on("modalidade");
            $table->integer("idCliente")->unsigned();
            $table->foreign("idCliente")->references("idCliente")->on("cliente");
            $table->timestamp("data")->default(\DB::raw("CURRENT_TIMESTAMP"));
            $table->text("observacao")->nullable();
        });

        \Schema::create("passeio", function($table) {
            $table->increments("idPasseio");
            $table->integer("idAgendamento")->unsigned();
            $table->foreign("idAgendamento")->references("idAgendamento")->on("agendamento");
            $table->integer("idLocal")->unsigned();
            $table->foreign("idLocal")->references("idLocal")->on("local");
            $table->integer("idPasseador")->unsigned()->nullable();
            $table->foreign("idPasseador")->references("idFuncionario")->on("funcionario");
            $table->integer("idPasseioReagendado")->unsigned()->nullable();
            $table->foreign("idPasseioReagendado")->references("idPasseio")->on("passeio");
            $table->decimal("preco", 6, 2);
            $table->boolean("coletivo")->default(false);
            $table->time("inicio");
            $table->time("fim");
            $table->date("data");
            $table->enum("status", ["pendente", "cancelado", "em_progresso", "feito"])->default("pendente");
        });

        \Schema::create("cancelamento", function($table) {
            $table->increments("idCancelamento");
            $table->integer("idPessoa")->unsigned();
            $table->integer("idPasseio")->unsigned();
            $table->foreign("idPasseio")->references("idPasseio")->on("passeio");
            $table->text("justificativa");
            $table->enum("status", ["verificado", "pendente"])->default("pendente");
            $table->date("data");
            $table->enum("tipoPessoa", ["funcionario", "cliente"]);
        });

        \Schema::create("a_cao_passeio", function($table) {
            $table->integer("idPasseio")->unsigned();
            $table->foreign("idPasseio")->references("idPasseio")->on("passeio");
            $table->integer("idCao")->unsigned();
            $table->foreign("idCao")->references("idCao")->on("cao");
            $table->primary(["idPasseio", "idCao"]);
        });

        \Schema::create("a_horario_dia", function($table) {
            $table->integer("idHorario")->unsigned();
            $table->foreign("idHorario")->references("idHorario")->on("horario");
            $table->integer("idDia")->unsigned();
            $table->foreign("idDia")->references("idDia")->on("dia");
            $table->primary(["idHorario", "idDia"]);
        });

        \Schema::create("a_local_imagem", function($table) {
            $table->integer("idLocal")->unsigned();
            $table->foreign("idLocal")->references("idLocal")->on("local");
            $table->integer("idImagem")->unsigned();
            $table->foreign("idImagem")->references("idImagem")->on("imagem");
            $table->tinyInteger("ordem")->unsigned()->nullable();
            $table->primary(["idLocal", "idImagem"]);
        });

        \Schema::create("a_agendamento_dia", function($table) {
            $table->integer("idAgendamento")->unsigned();
            $table->foreign("idAgendamento")->references("idAgendamento")->on("agendamento");
            $table->integer("idDia")->unsigned();
            $table->foreign("idDia")->references("idDia")->on("dia");
            $table->primary(["idDia", "idAgendamento"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::drop("a_agendamento_dia");
        \Schema::drop("a_local_imagem");
        \Schema::drop("a_horario_dia");
        \Schema::drop("a_cao_passeio");
        \Schema::drop("cancelamento");
        \Schema::drop("passeio");
        \Schema::drop("agendamento");
        \Schema::drop("vacinacao");
        \Schema::drop("cao");
        \Schema::drop("horario");
        \Schema::drop("funcionario");
        \Schema::drop("cliente");
        \Schema::drop("vacina");
        \Schema::drop("dia");
        \Schema::drop("modalidade");
        \Schema::drop("local");
        \Schema::drop("imagem_arquivo");
        \Schema::drop("imagem");
    }

}
