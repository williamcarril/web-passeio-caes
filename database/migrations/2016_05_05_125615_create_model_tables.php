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
        \DB::beginTransaction();
        try {
            \Schema::create("multimidia", function($table) {
                $table->increments("idMultimidia");
                $table->text("descricao")->nullable();
                $table->date("data");
                $table->string("arquivo", 255)->unique();
                $table->enum("tipo", ["imagem", "video"]);
            });

            \Schema::create("trajeto", function($table) {
                $table->increments("idTrajeto");
                $table->string("nome", 70)->unique();
                $table->text("descricao");
                $table->integer("raioAtuacao");
                $table->boolean("ativo")->default(true);
                $table->string("rua", 70);
                $table->string("bairro", 40);
                $table->char("postal", 8);
                $table->string("numero", 12);
                $table->decimal("lat", 10, 6);
                $table->decimal("lng", 10, 6);
            });

            \Schema::create("modalidade", function($table) {
                $table->increments("idModalidade");
                $table->string("nome", 35)->unique();
                $table->text("descricao");
                $table->enum("tipo", ["unitario", "pacote"]);
            });

            \Schema::create("modalidade_valor", function($table) {
                $table->increments("idModalidadeValor");
                $table->integer("idModalidade")->unsigned();
                $table->foreign("idModalidade")->references("idModalidade")->on("modalidade");
                $table->date("inicio");
                $table->date("fim");
                $table->decimal("preco", 6, 2);
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
                $table->string("telefone", 12);
                $table->boolean("ativo")->default(true);
                $table->char("cpf", 11);
                $table->string("rua", 70);
                $table->string("bairro", 40);
                $table->char("postal", 8);
                $table->string("numero", 12);
                $table->decimal("lat", 10, 6);
                $table->decimal("lng", 10, 6);
                $table->string("email", 80)->unique();
                $table->string("senha", 60);
                $table->rememberToken();
            });
            \Schema::create("funcionario", function($table) {
                $table->increments("idFuncionario");
                $table->string("nome", 70);
                $table->string("telefone", 12);
                $table->boolean("ativo")->default(true);
                $table->char("cpf", 11);
                $table->string("rua", 70);
                $table->string("bairro", 40);
                $table->char("postal", 8);
                $table->string("numero", 12);
                $table->decimal("lat", 10, 6);
                $table->decimal("lng", 10, 6);
                $table->string("rg", 15);
                $table->enum("tipo", ["passeador", "administrador"]);
                $table->string("email", 80)->unique();
                $table->string("senha", 60);
                $table->rememberToken();
            });

            \Schema::create("horario_interesse", function($table) {
                $table->increments("idHorarioInteresse");
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
                $table->enum("genero", ["macho", "femea"])->nullable();
                $table->integer("idCliente")->unsigned();
                $table->foreign("idCliente")->references("idCliente")->on("cliente");
                $table->integer("idMultimidia")->unsigned()->nullable();
                $table->foreign("idMultimidia")->references("idMultimidia")->on("multimidia");
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

            \Schema::create("passeio", function($table) {
                $table->increments("idPasseio");
                $table->integer("idModalidade")->unsigned();
                $table->foreign("idModalidade")->references("idModalidade")->on("modalidade");
                $table->integer("idTrajeto")->unsigned();
                $table->foreign("idTrajeto")->references("idTrajeto")->on("trajeto");
                $table->integer("idCliente")->unsigned();
                $table->foreign("idCliente")->references("idCliente")->on("cliente");
                $table->integer("idPasseador")->unsigned()->nullable();
                $table->foreign("idPasseador")->references("idFuncionario")->on("funcionario");
                $table->integer("idMultimidia")->unsigned()->nullable();
                $table->foreign("idMultimidia")->references("idMultimidia")->on("multimidia");
                $table->decimal("preco", 6, 2);
                $table->boolean("gravado")->default(false);
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
                $table->enum("status", ["feito", "pendente"])->default("pendente");
                $table->date("data");
                $table->enum("tipoPessoa", ["funcionario", "cliente"]);
            });

            \Schema::create("a_cao_passeio", function($table) {
                $table->integer("idPasseio")->unsigned();
                $table->foreign("idPasseio")->references("idPasseio")->on("passeio");
                $table->integer("idCao")->unsigned();
                $table->foreign("idCao")->references("idCao")->on("cao");
            });

            \Schema::create("a_horario_interesse_dia", function($table) {
                $table->integer("idHorarioInteresse")->unsigned();
                $table->foreign("idHorarioInteresse")->references("idHorarioInteresse")->on("horario_interesse");
                $table->integer("idDia")->unsigned();
                $table->foreign("idDia")->references("idDia")->on("dia");
                $table->boolean("interesse");
            });

            \Schema::create("a_trajeto_foto", function($table) {
                $table->integer("idTrajeto")->unsigned();
                $table->foreign("idTrajeto")->references("idTrajeto")->on("trajeto");
                $table->integer("idMultimidia")->unsigned();
                $table->foreign("idMultimidia")->references("idMultimidia")->on("multimidia");
                $table->tinyInteger("ordem")->unsigned()->nullable();
            });
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \DB::beginTransaction();
        try {
            \Schema::drop("a_trajeto_foto");
            \Schema::drop("a_horario_interesse_dia");
            \Schema::drop("a_cao_passeio");
            \Schema::drop("cancelamento");
            \Schema::drop("passeio");
            \Schema::drop("vacinacao");
            \Schema::drop("cao");
            \Schema::drop("horario_interesse");
            \Schema::drop("funcionario");
            \Schema::drop("cliente");
            \Schema::drop("vacina");
            \Schema::drop("dia");
            \Schema::drop("modalidade_valor");
            \Schema::drop("modalidade");
            \Schema::drop("trajeto");
            \Schema::drop("multimidia");
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
        }
    }

}
