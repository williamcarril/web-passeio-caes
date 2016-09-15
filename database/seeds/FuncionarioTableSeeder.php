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
            ],
            [
                "idFuncionario" => 2,
                "nome" => "Wilson Genuino",
                "telefone" => "1121544218",
                "ativo" => true,
                "cpf" => "48741023285",
                "logradouro" => "Rua Bel Aliance",
                "bairro" => "Jardim Sao Caetano",
                "postal" => "09581420",
                "numero" => "106-138",
                "lat" => -23.636766,
                "lng" => -46.577740,
                "email" => "wilsoncarril@hotmail.com.br",
                "senha" => \bcrypt("123"),
                "rg" => "911225341",
                "tipo" => "passeador",
                "idImagem" => 1
            ],
            [
                "idFuncionario" => 3,
                "nome" => "Victor Nagase",
                "telefone" => "1175244212",
                "ativo" => true,
                "cpf" => "42363697537",
                "logradouro" => "Rua Bel Aliance",
                "bairro" => "Jardim Sao Caetano",
                "postal" => "09581420",
                "numero" => "106-138",
                "lat" => -23.636766,
                "lng" => -46.577740,
                "email" => "victornb@msn.com",
                "senha" => \bcrypt("123"),
                "rg" => "216255131",
                "tipo" => "administrador",
                "idImagem" => 4
            ],
            [
                "idFuncionario" => 4,
                "nome" => "Walter Nagase",
                "telefone" => "1175244212",
                "ativo" => true,
                "cpf" => "42490798308",
                "logradouro" => "Rua Bel Aliance",
                "bairro" => "Jardim Sao Caetano",
                "postal" => "09581420",
                "numero" => "106-138",
                "lat" => -23.636766,
                "lng" => -46.577740,
                "email" => "walternb@msn.com",
                "senha" => \bcrypt("123"),
                "rg" => "546721347",
                "tipo" => "passeador",
                "idImagem" => 4
            ],
        ]);
    }

}
