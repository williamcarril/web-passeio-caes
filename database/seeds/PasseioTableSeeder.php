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
                "idLocal" => 2,
                "idPasseador" => 2,
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

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 1,
                "idAgendamento" => 1
            ]
        ]);

        \DB::table("passeio")->insert([
            [
                "idPasseio" => 2,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-09-19",
                "status" => "2016-09-19" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);

        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 2,
                "idCao" => 4
            ],
            [
                "idPasseio" => 2,
                "idCao" => 1
            ],
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 2,
                "idAgendamento" => 2
            ],
            [
                "idPasseio" => 2,
                "idAgendamento" => 3
            ]
        ]);

        \DB::table("passeio")->insert([
            [
                "idPasseio" => 3,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-09-21",
                "status" => "2016-09-21" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 3,
                "idCao" => 4
            ],
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 3,
                "idAgendamento" => 2
            ]
        ]);

        \DB::table("passeio")->insert([
            [
                "idPasseio" => 4,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-09-26",
                "status" => "2016-09-26" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 4,
                "idCao" => 4
            ]
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 4,
                "idAgendamento" => 2
            ]
        ]);

        \DB::table("passeio")->insert([
            [
                "idPasseio" => 5,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-09-28",
                "status" => "2016-09-28" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 5,
                "idCao" => 4
            ],
            [
                "idPasseio" => 5,
                "idCao" => 3
            ]
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 5,
                "idAgendamento" => 2
            ],
            [
                "idPasseio" => 5,
                "idAgendamento" => 4
            ]
        ]);

        \DB::table("passeio")->insert([
            [
                "idPasseio" => 6,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-10-03",
                "status" => "2016-10-03" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 6,
                "idCao" => 4
            ]
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 6,
                "idAgendamento" => 2
            ]
        ]);
        \DB::table("passeio")->insert([
            [
                "idPasseio" => 7,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-10-05",
                "status" => "2016-10-05" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 7,
                "idCao" => 4
            ]
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 7,
                "idAgendamento" => 2
            ]
        ]);
        \DB::table("passeio")->insert([
            [
                "idPasseio" => 8,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-10-10",
                "status" => "2016-10-10" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 8,
                "idCao" => 4
            ]
        ]);

        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 8,
                "idAgendamento" => 2
            ]
        ]);
        \DB::table("passeio")->insert([
            [
                "idPasseio" => 9,
                "idLocal" => 2,
                "idPasseador" => 2,
                "inicio" => "14:00:00",
                "fim" => "15:00:00",
                "data" => "2016-10-12",
                "status" => "2016-10-12" < date("Y-m-d") ? "feito" : "pendente",
                "coletivo" => true,
                "porte" => "pequeno"
            ]
        ]);
        \DB::table("a_agendamento_passeio")->insert([
            [
                "idPasseio" => 9,
                "idAgendamento" => 2
            ]
        ]);
        \DB::table("a_cao_passeio")->insert([
            [
                "idPasseio" => 9,
                "idCao" => 4
            ]
        ]);
    }

}
