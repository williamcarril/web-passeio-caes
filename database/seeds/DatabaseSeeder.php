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
        $this->call(ImagemTableSeeder::class);
        $this->call(LocalTableSeeder::class);
        $this->call(ClienteTableSeeder::class);
        $this->call(FuncionarioTableSeeder::class);

        Model::reguard();
    }

}
