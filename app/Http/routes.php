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

Route::get('/', function () {
    $model = new \App\Models\HorarioInteresse();

    if(!$model->save()) {
        return response()->json($model->getErrors());
    }
    return response()->view("home");
});
