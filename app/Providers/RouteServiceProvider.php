<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $adminNamespace = 'App\Http\Controllers';
    protected $walkerNamespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router) {
        //
        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router) {
        $this->mapWebRoutes($router);
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router) {
        
        //Walker routes
        $router->group([
            "namespace" => $this->walkerNamespace, "middleware" => "walker", "prefix" => "walker"
        ], function($router) {
            require app_path("Http/routes.walker.php");
        });
        
        //Admin routes
        $router->group([
            'namespace' => $this->adminNamespace, 'middleware' => 'admin', "prefix" => "admin"
                ], function ($router) {
            require app_path('Http/routes.admin.php');
        });
        
        //Web routes
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
                ], function ($router) {
            require app_path('Http/routes.php');
        });
    }

}
