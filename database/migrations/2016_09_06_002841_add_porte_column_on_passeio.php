<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPorteColumnOnPasseio extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \Schema::table("passeio", function($table) {
            $table->enum("porte", ["pequeno", "medio", "grande"])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        \Schema::table("passeio", function($table) {
            $table->dropColumn("porte");
        });
    }

}
