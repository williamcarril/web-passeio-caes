<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Eloquent\Vacina;

class VacinaController extends ResourceController {

    public function create() {
        return response()->json(Vacina::getRules());
    }

    public function doDestroy($id) {
        $model = Vacina::findOrFail($id);

        $status = $model->delete();

        return ["status" => $status, "messages" => []];
    }

    public function doStore(Request $request) {
        $model = new Vacina();
        $model->nome = $request->get("nome");
        $status = $model->save();
        $messages = $model->getErrors();
        return ["status" => $status, "messages" => $messages];
    }

    public function doUpdate(Request $request, $id) {
        $model = Vacina::findOrFail($id);
        if ($request->has("nome")) {
            $model->nome = $request->get("nome");
        }
        $status = $model->save();
        $messages = $model->getErrors();
        return ["status" => $status, "messages" => $messages];
    }

    public function edit($id) {
        return $this->create();
    }

    public function index() {
        $data = Vacina::all();
        return response()->json($data);
    }

    public function show($id) {
        $model = Vacina::findOrFail($id);
        return response()->json($model);
    }

}
