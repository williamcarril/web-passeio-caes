<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAgendamentoPasseioTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::table("agendamento", function($table) {
            $table->enum("status", ["feito", "cancelado", "pendente_funcionario", "pendente_cliente"]);
            $table->integer("idAgendamentoNovo")->unsigned()->nullable();
            $table->foreign("idAgendamentoNovo")->references("idAgendamento")->on("agendamento");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::table("agendamento", function($table) {
            $table->dropColumn("status");
            $table->dropForeign(["idAgendamentoNovo"]);
            $table->dropColumn("idAgendamentoNovo");
        });
    }

}
