<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgendamentoCaoAssociativeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::create("a_agendamento_cao", function($table) {
            $table->integer("idAgendamento")->unsigned();
            $table->foreign("idAgendamento")->references("idAgendamento")->on("agendamento");
            $table->integer("idCao")->unsigned();
            $table->foreign("idCao")->references("idCao")->on("cao");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::drop("a_agendamento_cao");
    }

}
