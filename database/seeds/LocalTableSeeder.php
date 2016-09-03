<?php

use Illuminate\Database\Seeder;

class LocalTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::beginTransaction();
        try {
            \DB::table("local")->insert([
                [
                    "idLocal" => 1,
                    "nome" => "Parque Ibirapuera",
                    "descricao" => "O Parque Ibirapuera é o mais importante parque urbano da cidade de São Paulo, no Brasil. Foi inaugurado em 21 de agosto de 1954 para a comemoração do quarto centenário da cidade.",
                    "raioAtuacao" => 2000,
                    "ativo" => true,
                    "logradouro" => "Av. Pedro Álvares Cabral",
                    "bairro" => "Vila Mariana",
                    "postal" => "04094000",
                    "numero" => null,
                    "lat" => -23.5848,
                    "lng" => -46.6559,
                    "complemento" => null,
                    "slug" => "ibirapuera"
                ]
            ]);
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }

}
