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

            \DB::table("a_local_imagem")->insert([
                "idLocal" => 1, "idImagem" => 3
            ]);
            
            \DB::table("local")->insert([
                [
                    "idLocal" => 2,
                    "nome" => "Chico Mendes",
                    "descricao" => "Possui sete quadras poliesportivas (futebol, basquete, handebol e vôlei); playgrounds; pistas de Cooper coberta e descoberta; mesas para jogos de dama, xadrez e pingue-pongue; quiosques; lanchonete; cafeteria; revistaria; lago; palco coberto com lonas distendidas; estacionamento; salas da Fundação Pró-Memória, Diretoria de Esportes e Turismo e de atendimento médico; portaria e administração; banheiros; telefones públicos. A Praça Armando Furlan, com 12 mil m²; fonte; monumento, com um globo de metal que destaca São Caetano no mapa mundi, e 11 miniglobos, que representam a inserção da cidade no mundo; jardim com seis floreiras e 40 bancos.
                        Há 4 percursos (pistas e trilhas) principais, definidos e aferidos pela Associação Internacional de Maratonas e Corridas de Ruas que têm 570, 840, 1.100 e 1.600 metros de extensão (há placa indicativa desses percursos no local).",
                    "raioAtuacao" => 5000,
                    "ativo" => true,
                    "logradouro" => "Avenida Fernando Símonsen",
                    "bairro" => "Cerâmica",
                    "postal" => "09540230",
                    "numero" => 566,
                    "lat" => -23.6319783,
                    "lng" => -46.57196959999999,
                    "complemento" => null,
                    "slug" => "chico-mendes"
                ]
            ]);

            \DB::table("a_local_imagem")->insert([
                "idLocal" => 2, "idImagem" => 5
            ]);
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollBack();
            throw $ex;
        }
    }

}
