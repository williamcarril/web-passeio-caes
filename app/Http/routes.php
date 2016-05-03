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
    Route::group(["prefix" => "teste"], function() {
        Route::get("/", function() {
            $model = new \App\Models\HorarioInteresse();
            $model->save();
            return response()->json($model->getErrors());
        });
    });
}

Route::get('/', function () {
    return response()->view("home");
});

Route::group(["prefix" => "api"], function() {
    Route::resource("modalidade", "ModalidadeController");
    Route::resource("vacina", "VacinaController");
    Route::resource("trajeto", "TrajetoController");
});
