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
    $model = new \App\Models\Funcionario();
    echo json_encode($model->passeios);
    exit;
    if(!$model->save()) {
        return response()->json($model->getErrors());
    }
    return response()->view("home");
});
