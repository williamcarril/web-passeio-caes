<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class ResourceController extends Controller {

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public abstract function index();

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public abstract function create();

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        \DB::beginTransaction();
        try {
            $stored = $this->doStore($request);

            $status = $stored["status"];
            $messages = $stored["messages"];

            if ($status) {
                \DB::commit();
            } else {
                \DB::rollback();
            }
        } catch (\Exception $ex) {
            $status = false;
            $messages = ["error" => $ex->getMessage()];
            \DB::rollback();
        }
        return response()->json(["status" => $status, "messages" => $messages]);
    }

    /**
     * Prepara a classe de modelo para persistência e retorna o objeto.
     * É a primeira operação realizada pelo método 'store'.
     * Deve retornar um array no seguinte formato:
     * [
     *  "status": boolean,
     *  "messages": array
     * ]
     * @param Request $request
     * @return array
     */
    public abstract function doStore(Request $request);

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public abstract function show($id);

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public abstract function edit($id);

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        \DB::beginTransaction();
        try {
            $updated = $this->doUpdate($request, $id);

            $status = $updated["status"];
            $messages = $updated["messages"];

            if ($status) {
                \DB::commit();
            } else {
                \DB::rollback();
            }
        } catch (\Exception $ex) {
            $status = false;
            $messages = ["error" => $ex->getMessage()];
            \DB::rollback();
        }
        return response()->json(["status" => $status, "messages" => $messages]);
    }

    /**
     * Prepara a classe de modelo para persistência e retorna o objeto.
     * É a primeira operação realizada pelo método 'update'.
     * Deve retornar um array no seguinte formato:
     * [
     *  "status": boolean,
     *  "messages": array
     * ]
     * @param Request $request
     * @return array
     */
    public abstract function doUpdate(Request $request, $id);

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        \DB::beginTransaction();
        try {
            $deleted = $this->doDestroy($id);

            $status = $deleted["status"];
            $messages = $deleted["messages"];

            if ($status) {
                \DB::commit();
            } else {
                \DB::rollback();
            }
        } catch (\Exception $ex) {
            $status = false;
            $messages = ["error" => $ex->getMessage()];
            \DB::rollback();
        }
        return response()->json(["status" => $status, "messages" => $messages]);
    }

    /**
     * Remove a entidade representada pelo ID e retorna o sucesso da operação.
     * É a primeira operação realizada pelo método 'destroy'.
     * Deve retornar um array no seguinte formato:
     * [
     *  "status": boolean,
     *  "messages": array
     * ]
     * @param int $id
     * @return array
     */
    public abstract function doDestroy($id);
}
