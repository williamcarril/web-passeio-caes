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
        \Validator::extend("date_greater_than", "App\\Util\\Validator@checkDateGreaterThanValue");
        \Validator::extend("greater_or_equal", "App\\Util\\Validator@checkGreaterOrEqualThan");
        \Validator::extend("date_greater_or_equal", "App\\Util\\Validator@checkDateGreaterOrEqualThan");
        \Validator::extend("less", "App\\Util\\Validator@checkLessThan");
        \Validator::extend("date_less_than", "App\\Util\\Validator@checkDateLessThanValue");
        \Validator::extend("less_or_equal", "App\\Util\\Validator@checkLessOrEqualThan");
        \Validator::extend("date_less_or_equal", "App\\Util\\Validator@checkDateLessOrEqualThan");
        \Validator::extend("image_array", "App\\Util\\Validator@checkImageArray");
        \Validator::extend("phone", "App\\Util\\Validator@checkPhone");

        Relation::morphMap([
            'cliente' => \App\Models\Eloquent\Cliente::class,
            'funcionario' => \App\Models\Eloquent\Funcionario::class,
            "cao" => \App\Models\Eloquent\Cao::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(\Intervention\Image\ImageManager::class, function($app) {
            return new \Intervention\Image\ImageManager();
        });

        $this->app->singleton(\App\Models\File\Repositorio::class, function ($app) {
            $repository = new \App\Models\File\Repositorio($app["filesystem"], $app->make(\Intervention\Image\ImageManager::class));
            return $repository;
        });

        $this->app->singleton(\App\Models\Address\CepResearcher::class, function($app) {
            $researcher = new \App\Models\Address\ViaCep();
            return $researcher;
        });
    }

}
