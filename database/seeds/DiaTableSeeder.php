<?php

use Illuminate\Database\Seeder;

class DiaTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::beginTransaction();
        try {
            \DB::table("dia")->insert([
                ["idDia" => 1, "nome" => "domingo"],
                ["idDia" => 2, "nome" => "segunda-feira"],
                ["idDia" => 3, "nome" => "terça-feira"],
                ["idDia" => 4, "nome" => "quarta-feira"],
                ["idDia" => 5, "nome" => "quinta-feira"],
                ["idDia" => 6, "nome" => "sexta-feira"],
                ["idDia" => 7, "nome" => "sábado"],
            ]);
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
    }

}
