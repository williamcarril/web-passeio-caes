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
                    "logradouro" => "Rua Aparecida",
                    "bairro" => "Boa Vista",
                    "postal" => "09572210",
                    "numero" => "239",
                    "lat" => -23.637577,
                    "lng" => -46.561561,
                    "email" => "williamcarril@terra.com.br",
                    "senha" => \bcrypt("123")
                ],
                [
                    "idCliente" => 2,
                    "nome" => "José Afonso",
                    "telefone" => "11911422215",
                    "ativo" => true,
                    "cpf" => "53716059676",
                    "logradouro" => "Estrada das Lágrimas",
                    "bairro" => "Jardim Sao Caetano",
                    "postal" => "09580500",
                    "numero" => "1691",
                    "lat" => -23.644733,
                    "lng" => -46.574693,
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
                [
                    "idCliente" => 4,
                    "nome" => "Gabriel C. Silva",
                    "telefone" => "1182157431",
                    "ativo" => true,
                    "cpf" => "92029499307",
                    "logradouro" => "Rua Antônio de Andrade",
                    "bairro" => "Cerâmica",
                    "postal" => "09540240",
                    "numero" => "186",
                    "lat" => -23.631219,
                    "lng" => -46.570916,
                    "email" => "gc@hotmail.com",
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
