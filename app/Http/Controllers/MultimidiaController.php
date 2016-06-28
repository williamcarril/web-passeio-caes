<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Multimidia;
use App\Models\File\Repositorio;

class MultimidiaController extends ResourceController {

    private $repository;

    public function __construct(Repositorio $repository) {
        parent::__construct();
        $this->repository = $repository;
    }

    public function create() {
        $rules = Multimidia::getRules();
        if (isset($rules["data"])) {
            unset($rules["data"]);
        }
        return response()->json($rules);
    }

    public function doDestroy($id) {
        $model = Multimidia::findOrFail($id);
        $file = $model->arquivo;
        $status = $model->delete();
        if ($status) {
            try {
                $this->repository->delete($file);
            } catch (\Exception $ex) {
                
            }
        }

        return ["status" => $status, "messages" => []];
    }

    public function doStore(Request $request) {
        $model = new Multimidia();

        $model->nome = $request->input("nome");
        $model->descricao = $request->input("descricao");

        $file = $request->file("arquivo");

        if ($this->isAnImage($file)) {
            $model->tipo = "imagem";
        }

        if ($this->isAVideo($file)) {
            $model->tipo = "video";
        }

        $filename = $this->repository->save($file);
        if ($filename === false) {
            return ["status" => false, "messages" => ["The file couldn't be saved."]];
        }
        $model->arquivo = $filename;

        $status = $model->save();
        $messages = $model->getErrors();

        return ["status" => $status, "messages" => $messages];
    }

    public function doUpdate(Request $request, $id) {
        $model = Multimidia::findOrFail($id);

        if ($request->has("nome")) {
            $model->nome = $request->input("nome");
        }
        if ($request->has("descricao")) {
            $model->descricao = $request->input("descricao");
        }

        if ($request->hasFile("arquivo")) {
            $file = $request->file("arquivo");
            if ($this->isAnImage($file)) {
                $model->tipo = "imagem";
            }
            if ($this->isAVideo($file)) {
                $model->tipo = "video";
            }
            $filename = $this->repository->save($file);
            if ($filename === false) {
                return ["status" => false, "messages" => ["The file couldn't be saved."]];
            }
            $model->arquivo = $filename;
        }

        $status = $model->save();
        $messages = $model->getErrors();

        return ["status" => $status, "messages" => $messages];
    }

    public function edit($id) {
        return $this->create();
    }

    public function index() {
        $data = Multimidia::all();
        return response()->json($data);
    }

    public function show($id) {
        $model = Multimidia::findOrFail($id);
        return response()->json($model);
    }

    private function isAnImage($file) {
        $validator = validator(["file" => $file->getPathname()], ["file" => "image"]);
        return !$validator->fails();
    }

    private function isAVideo($file) {
        $validator = validator(["file" => $file->getPathname()], ["file" => "mimes:mp4,avi,wmv"]);
        return !$validator->fails();
    }

}
