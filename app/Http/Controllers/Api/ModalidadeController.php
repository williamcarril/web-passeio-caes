<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Eloquent\Modalidade;

class ModalidadeController extends ResourceController {

    public function index() {
        $data = Modalidade::all();
        return response()->json($data);
    }

    public function create() {
        return response()->json(Modalidade::getRules());
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
        if ($request->has("nome")) {
            $model->nome = $request->get("nome");
        }
        if ($request->has("descricao")) {
            $model->descricao = $request->get("descricao");
        }
        if ($request->has("tipo")) {
            $model->tipo = $request->get("tipo");
        }

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
