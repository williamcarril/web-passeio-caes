<?php

use Illuminate\Database\Seeder;

class FuncionarioLimiteCaesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("funcionario_limite_caes")->insert([
            [
                "idFuncionario" => 2,
                "porte" => "grande",
                "limite" => 3
            ],
            [
                "idFuncionario" => 2,
                "porte" => "medio",
                "limite" => 6
            ],
            [
                "idFuncionario" => 4,
                "porte" => "grande",
                "limite" => 4
            ],
            [
                "idFuncionario" => 4,
                "porte" => "medio",
                "limite" => 8
            ]
        ]);
    }

}
