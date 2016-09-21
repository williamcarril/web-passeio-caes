<?php

use Illuminate\Database\Seeder;

class ModalidadeTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("modalidade")->insert([
            [
                "idModalidade" => 1,
                "nome" => "Passeio Simples (unitário)",
                "descricao" => "Trata-se de um passeio não-coletivo unitário do intervalo de horas determinado pelo cliente.",
                "tipo" => "unitario",
                "periodo" => null,
                "frequencia" => null,
                "ativo" => true,
                "coletivo" => false,
                "precoPorCaoPorHora" => 25
            ],
            [
                "idModalidade" => 2,
                "nome" => "Passeio Coletivo (unitário)",
                "descricao" => "Trata-se de um passeio coletivo unitário do intervalo de horas determinado pelo cliente.",
                "tipo" => "unitario",
                "periodo" => null,
                "frequencia" => null,
                "ativo" => false,
                "coletivo" => true,
                "precoPorCaoPorHora" => 18
            ],
            [
                "idModalidade" => 3,
                "nome" => "Passeio Coletivo (pacote)",
                "descricao" => "Trata-se de um pacote de passeios coletivos, duas vezes por semana, durante um período de um mês, pelo intervalo de horas determinado pelo cliente.",
                "tipo" => "pacote",
                "periodo" => "mensal",
                "frequencia" => "bisemanal",
                "ativo" => true,
                "coletivo" => true,
                "precoPorCaoPorHora" => 15
            ],
        ]);
    }

}
