<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterModalidadePreco extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::table("modalidade", function($table) {
            $table->dropColumn("precoPorPasseio");
        });

        \Schema::create("modalidade_preco", function($table) {
            $table->integer("idModalidade")->unsigned();
            $table->integer("quantidadeCaes")->unsigned()->nullable();
            $table->decimal("valor", 6, 2)->unsigned();
            $table->primary(["idModalidade", "quantidadeCaes"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::drop("modalidade_preco");
        \Schema::table("modalidade", function($table) {
            $table->decimal("precoPorPasseio", 6, 2)->unsigned();
        });
    }

}
