<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class TrajetoController extends ResourceController {

    public function create() {
        $fields = [
            "nome",
            "descricao",
            "raioAtuacao",
            "ativo",
            "rua",
            "bairro",
            "postal",
            "numero",
            "lat",
            "lng"
        ];
        return response()->json($fields);
    }

    public function doDestroy($id) {
        
    }

    public function doStore(Request $request) {
        
    }

    public function doUpdate(Request $request, $id) {
        
    }

    public function edit($id) {
        
    }

    public function index() {
        
    }

    public function show($id) {
        
    }

}
