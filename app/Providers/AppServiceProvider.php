<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        \Validator::extend('cpf', "App\\Util\\Validator@checkCpf");
        \Validator::extend('cep', "App\\Util\\Validator@checkCep");
        Relation::morphMap([
            'cliente' => \App\Models\Cliente::class,
            'funcionario' => \App\Models\Funcionario::class,
            "cao" => \App\Models\Cao::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        
    }

}
