<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Eloquent\Trajeto;

/**
 * @todo doDestroy, doStore, doCreate
 */
class TrajetoController extends ResourceController {

    private $multimidiaCtl;

    public function __construct(MultimidiaController $multimidiaCtl) {
        $this->multimidiaCtl = $multimidiaCtl;
    }

    public function create() {
        $rules = Trajeto::getRules();
        $rules["fotos"] = [
            "image_array"
        ];
        return response()->json($rules);
    }

    //Corrigir relação de trajeto com foto...
    public function doDestroy($id) {
        $model = Trajeto::findOrFail($id);

        $status = $model->delete();

        return ["status" => $status, "messages" => []];
    }

    public function doStore(Request $request) {
        $model = new Trajeto();

        $model->nome = $request->input("nome");
        $model->raioAtuacao = $request->input("raioAtuacao");
        $model->ativo = $request->input("ativo", false);
        $model->rua = $request->input("rua");
        $model->bairro = $request->input("bairro");
        $model->postal = $request->input("postal");
        $model->numero = $request->input("numero");
        $model->lat = $request->input("lat");
        $model->lng = $request->input("lng");

        $fotos = $request->file("fotos");
        //Salvar multimídias...

        $status = $model->save();
        $messages = $model->getErrors();

        return ["status" => $status, "messages" => $messages];
    }

    public function doUpdate(Request $request, $id) {
        
    }

    public function edit($id) {
        return $this->create();
    }

    public function index() {
        $data = Trajeto::all();
        return response()->json($data);
    }

    public function show($id) {
        $model = Trajeto::findOrFail($id);
        return response()->json($model);
    }

}
