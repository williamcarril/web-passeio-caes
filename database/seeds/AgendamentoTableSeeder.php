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
                "idCliente" => 2,
                "idModalidade" => 2,
                "data" => date("Y-m-d"),
                "status" => "feito"
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
    }

}
