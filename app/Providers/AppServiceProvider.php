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
        \Validator::extend("dataInicial", function($attribute = null, $inicial = null, $parameters = [], $validator = null) {
            $data = $validator->getData();
            $field = isset($parameters[0]) ? $parameters[0] : "fim";
            $final = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($final)) {
                return true;
            }
            return $inicial <= $final;
        });
        \Validator::extend("dataFinal", function($attribute = [], $final = [], $parameters = [], $validator) {
            $data = $validator->getData();
            $field = isset($parameters[0]) ? $parameters[0] : "inicio";
            $inicial = isset($data["$field"]) ? $data["$field"] : null;
            if (is_null($inicial)) {
                return true;
            }
            return $final >= $inicial;
        });
        
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
