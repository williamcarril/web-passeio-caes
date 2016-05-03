<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trajeto;

class TrajetoController extends ResourceController {

    public function create() {
        $rules = Trajeto::getRules();
        $rules["fotos"] = [
            "image_array"
        ];
        return response()->json($rules);
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
