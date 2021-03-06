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
                "precoPorCaoPorHora" => 12
            ],
            [
                "idModalidade" => 2,
                "nome" => "Passeio Coletivo (unitário)",
                "descricao" => "Trata-se de um passeio coletivo unitário do intervalo de horas determinado pelo cliente.",
                "tipo" => "unitario",
                "periodo" => null,
                "frequencia" => null,
                "ativo" => true,
                "coletivo" => true,
                "precoPorCaoPorHora" => 8.75
            ],
            [
                "idModalidade" => 3,
                "nome" => "Passeio Coletivo (pacote bisemanal)",
                "descricao" => "Trata-se de um pacote de passeios coletivos, duas vezes por semana, durante um período de um mês, pelo intervalo de horas determinado pelo cliente.",
                "tipo" => "pacote",
                "periodo" => "mensal",
                "frequencia" => "bisemanal",
                "ativo" => true,
                "coletivo" => true,
                "precoPorCaoPorHora" => 6.5
            ],
            [
                "idModalidade" => 4,
                "nome" => "Passeio Coletivo (pacote semanal)",
                "descricao" => "Trata-se de um pacote de passeios coletivos, uma vez por semana, durante um período de um mês, pelo intervalo de horas determinado pelo cliente.",
                "tipo" => "pacote",
                "periodo" => "mensal",
                "frequencia" => "semanal",
                "ativo" => true,
                "coletivo" => true,
                "precoPorCaoPorHora" => 7.5
            ],
            [
                "idModalidade" => 5,
                "nome" => "Passeio Simples (pacote semanal)",
                "descricao" => "Trata-se de um pacote de passeios simples, uma vez por semana, durante um período de um mês, pelo intervalo de horas determinado pelo cliente.",
                "tipo" => "pacote",
                "periodo" => "mensal",
                "frequencia" => "semanal",
                "ativo" => true,
                "coletivo" => false,
                "precoPorCaoPorHora" => 10
            ],
        ]);
    }

}
