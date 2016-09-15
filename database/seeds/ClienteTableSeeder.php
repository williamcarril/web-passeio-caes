<?php

use Illuminate\Database\Seeder;

class ClienteTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::beginTransaction();
        try {
            \DB::table("cliente")->insert([
                [
                    "idCliente" => 1,
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
                    "senha" => \bcrypt("123")
                ],
                [
                    "idCliente" => 2,
                    "nome" => "José Afonso",
                    "telefone" => "11911422215",
                    "ativo" => true,
                    "cpf" => "53716059676",
                    "logradouro" => "Rua Bel Aliance",
                    "bairro" => "Jardim Sao Caetano",
                    "postal" => "09581420",
                    "numero" => "106-138",
                    "lat" => -23.636766,
                    "lng" => -46.577740,
                    "email" => "joseafonso@gmail.com",
                    "senha" => \bcrypt("123")
                ],
                [
                    "idCliente" => 3,
                    "nome" => "Victor Nagase",
                    "telefone" => "1175244212",
                    "ativo" => true,
                    "cpf" => "31750391465",
                    "logradouro" => "Rua Bel Aliance",
                    "bairro" => "Jardim Sao Caetano",
                    "postal" => "09581420",
                    "numero" => "106-138",
                    "lat" => -23.636766,
                    "lng" => -46.577740,
                    "email" => "victornb@msn.com",
                    "senha" => \bcrypt("123")
                ],
            ]);
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }

}
