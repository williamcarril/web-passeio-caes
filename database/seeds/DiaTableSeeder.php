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
                ["idDia" => 1, "nome" => "domingo", "ordem" => 1],
                ["idDia" => 2, "nome" => "segunda-feira", "ordem" => 2],
                ["idDia" => 3, "nome" => "terça-feira", "ordem" => 3],
                ["idDia" => 4, "nome" => "quarta-feira", "ordem" => 4],
                ["idDia" => 5, "nome" => "quinta-feira", "ordem" => 5],
                ["idDia" => 6, "nome" => "sexta-feira", "ordem" => 6],
                ["idDia" => 7, "nome" => "sábado", "ordem" => 7],
            ]);
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }

}
