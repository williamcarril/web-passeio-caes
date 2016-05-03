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
        \Validator::extend("greater", "App\\Util\\Validator@checkGreaterThan");
        \Validator::extend("greater_or_equal", "App\\Util\\Validator@checkGreaterOrEqualThan");
        \Validator::extend("less", "App\\Util\\Validator@checkLessThan");
        \Validator::extend("less_or_equal", "App\\Util\\Validator@checkLessOrEqualThan");
        
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
