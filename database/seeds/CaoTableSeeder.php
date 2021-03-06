<?php

use Illuminate\Database\Seeder;

class CaoTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("cao")->insert([
            [
                "idCao" => 1,
                "nome" => "Cleiton",
                "raca" => "Shiba Inu",
                "porte" => "pequeno",
                "genero" => "macho",
                "idCliente" => 1,
                "idImagem" => 2,
                "ativo" => true
            ],
            [
                "idCao" => 2,
                "nome" => "Frederico",
                "raca" => "Pastor Alemão",
                "porte" => "grande",
                "genero" => "macho",
                "idCliente" => 1,
                "idImagem" => 6,
                "ativo" => true
            ],
            [
                "idCao" => 3,
                "nome" => "Mercedes",
                "raca" => "Pinscher",
                "porte" => "pequeno",
                "genero" => "femea",
                "idCliente" => 3,
                "idImagem" => 7,
                "ativo" => true
            ],
            [
                "idCao" => 4,
                "nome" => "Rodolfo",
                "raca" => "Pug",
                "porte" => "pequeno",
                "genero" => "macho",
                "idCliente" => 4,
                "idImagem" => 8,
                "ativo" => true
            ],
            [
                "idCao" => 5,
                "nome" => "Altair",
                "raca" => "Pinscher",
                "porte" => "pequeno",
                "genero" => "macho",
                "idCliente" => 3,
                "idImagem" => 9,
                "ativo" => true
            ]
        ]);
    }

}
