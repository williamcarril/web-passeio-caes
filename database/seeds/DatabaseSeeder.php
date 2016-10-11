<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();
        
        $this->call(DiaTableSeeder::class);
        $this->call(ModalidadeTableSeeder::class);
        $this->call(ImagemTableSeeder::class);
        $this->call(LocalTableSeeder::class);
        $this->call(ClienteTableSeeder::class);
        $this->call(CaoTableSeeder::class);
        $this->call(FuncionarioTableSeeder::class);
        $this->call(FuncionarioLimiteCaesTableSeeder::class);
        $this->call(AgendamentoTableSeeder::class);
        $this->call(PasseioTableSeeder::class);
        Model::reguard();
    }

}
