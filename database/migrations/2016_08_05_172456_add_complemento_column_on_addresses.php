<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComplementoColumnOnAddresses extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        \DB::beginTransaction();
        try {
            \Schema::table("trajeto", function($table) {
                $table->string("complemento", 50)->nullable();
            });
            \Schema::table("cliente", function($table) {
                $table->string("complemento", 50)->nullable();
            });
            \Schema::table("funcionario", function($table) {
                $table->string("complemento", 50)->nullable();
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
            \Schema::table("trajeto", function($table) {
                $table->dropColumn("complemento");
            });
            \Schema::table("cliente", function($table) {
                $table->dropColumn("complemento");
            });
            \Schema::table("funcionario", function($table) {
                $table->dropColumn("complemento");
            });
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
    }

}
