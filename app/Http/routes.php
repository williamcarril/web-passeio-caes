<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
if (!\App::environment("production")) {
    Route::group(["prefix" => "tests"], function() {
        Route::get("/", ["as" => "test", "uses" => function() {
            return view("test");
        }]);
        Route::post("/", ["as" => "test.post", "uses" => function() {
            $file = \Request::file("file");
            $r = \App::make("App\Models\File\Repositorio");
            return response()->json($r->save($file));
        }]);
    });
}

Route::get('/', ["as" => "home", "uses" => function () {
    return response()->view("home");
}]);

Route::group(["prefix" => "api"], function() {
    Route::resource("modalidade", "ModalidadeController");
    Route::resource("vacina", "VacinaController");
    Route::resource("trajeto", "TrajetoController");
    Route::resource("multimidia", "MultimidiaController");
});
