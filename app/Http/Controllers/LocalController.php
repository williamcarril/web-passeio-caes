<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Local;

class LocalController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas que retornam Views">
    public function route_getLocais() {
        $data = [];
        return response()->view("local.listagem", $data);
    }

    public function route_getLocal($slug) {
        $local = Local::where("slug", $slug)->firstOrFail();
        $data = [];
        return response()->view("local.detalhes", $data);
    }

    // </editor-fold>
}
