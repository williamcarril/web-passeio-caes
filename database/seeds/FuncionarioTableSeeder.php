<?php

use Illuminate\Database\Seeder;

class FuncionarioTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("funcionario")->insert([
            [
                "idFuncionario" => 1,
                "nome" => "William Carril",
                "telefone" => "1121544218",
                "ativo" => true,
                "cpf" => "44173941803",
                "logradouro" => "Rua Bel Aliance",
                "bairro" => "Jardim Sao Caetano",
                "postal" => "09581420",
                "numero" => "106-138",
                "lat" => -23.636766,
                "lng" => -46.577740,
                "email" => "williamcarril@terra.com.br",
                "senha" => \bcrypt("123"),
                "rg" => "475977105",
                "tipo" => "administrador",
                "idImagem" => 1
            ]
        ]);
    }

}
