<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modalidade;

class ModalidadeController extends ResourceController {

    public function index() {
        $data = Modalidade::all();
        return response()->json($data);
    }

    public function create() {
        return response()->json(["nome", "descricao", "tipo"]);
    }

    public function doStore(Request $request) {
        $model = new Modalidade();
        $model->nome = $request->get("nome");
        $model->descricao = $request->get("descricao");
        $model->tipo = $request->get("tipo");

        $status = $model->save();
        $messages = $model->getErrors();
        return ["status" => $status, "messages" => $messages];
    }

    public function show($id) {
        $model = Modalidade::findOrFail($id);
        return response()->json($model);
    }

    public function edit($id) {
        return $this->create();
    }

    public function doUpdate(Request $request, $id) {
        $model = Modalidade::findOrFail($id);
        $model->nome = $request->get("nome");
        $model->descricao = $request->get("descricao");
        $model->tipo = $request->get("tipo");

        $status = $model->save();
        $messages = $model->getErrors();
        return ["status" => $status, "messages" => $messages];
    }

    public function doDestroy($id) {
        $model = Modalidade::findOrFail($id);

        $status = $model->delete();

        return ["status" => $status, "messages" => []];
    }

}
