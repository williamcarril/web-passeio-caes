<?php

use Illuminate\Database\Seeder;

class PasseioTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("passeio")->insert([
            [
                "idPasseio" => 1,
                "idAgendamento" => 1,
                "idLocal" => 2,
                "idPasseador" => 2,
                "precoPorCaoPorHora" => 25,
                "inicio" => "16:00:00",
                "fim" => "17:00:00",
                "data" => date("Y-m-d", strtotime("-2 day")),
                "status" => "feito",
                "coletivo" => false,
                "porte" => null
            ]
        ]);
        
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 1,
                "idCao" => 1
            ]
        ]);
    }

}
