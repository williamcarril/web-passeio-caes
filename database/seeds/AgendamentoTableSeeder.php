<?php

use Illuminate\Database\Seeder;

class AgendamentoTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("agendamento")->insert([
            [
                "idAgendamento" => 1,
                "idCliente" => 1,
                "idModalidade" => 1,
                "data" => date("Y-m-d"),
                "precoPorCaoPorHora" => 25,
                "status" => "feito"
            ]
        ]);

        \DB::table("a_agendamento_cao")->insert([
            [
                "idCao" => 1,
                "idAgendamento" => 1
            ]
        ]);

        \DB::table("agendamento")->insert([
            [
                "idAgendamento" => 2,
                "idCliente" => 4,
                "idModalidade" => 3,
                "data" => date("Y-m-d"),
                "status" => "feito",
                "precoPorCaoPorHora" => 15,
            ]
        ]);

        \DB::table("a_agendamento_cao")->insert([
            [
                "idCao" => 4,
                "idAgendamento" => 2
            ]
        ]);

        \DB::table("a_agendamento_dia")->insert([
            [
                "idAgendamento" => 2,
                "idDia" => 2
            ],
            [
                "idAgendamento" => 2,
                "idDia" => 4
            ],
        ]);

        \DB::table("agendamento")->insert([
            [
                "idAgendamento" => 3,
                "idCliente" => 1,
                "idModalidade" => 2,
                "data" => date("Y-m-d"),
                "status" => "feito",
                "precoPorCaoPorHora" => 18,
            ]
        ]);

        \DB::table("a_agendamento_cao")->insert([
            [
                "idCao" => 1,
                "idAgendamento" => 3
            ]
        ]);
        
        \DB::table("agendamento")->insert([
            [
                "idAgendamento" => 4,
                "idCliente" => 3,
                "idModalidade" => 2,
                "data" => date("Y-m-d"),
                "status" => "feito",
                "precoPorCaoPorHora" => 18,
            ]
        ]);

        \DB::table("a_agendamento_cao")->insert([
            [
                "idCao" => 3,
                "idAgendamento" => 4
            ]
        ]);
    }

}
