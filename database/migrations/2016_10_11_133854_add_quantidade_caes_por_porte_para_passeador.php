<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantidadeCaesPorPorteParaPasseador extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::create("funcionario_limite_caes", function($table) {
            $table->integer("idFuncionario")->unsigned();
            $table->foreign("idFuncionario")->references("idFuncionario")->on("funcionario");
            
            $table->enum("porte", ["pequeno", "medio", "grande"]);
            $table->integer("limite")->unsigned();
            
            $table->primary(["idFuncionario", "porte"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::drop("funcionario_limite_caes");
    }

}
