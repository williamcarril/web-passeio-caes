<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class ClienteController extends Controller {

    public function route_getCadastro(Request $req) {
        $data = [];
        
        return response()->view("", $data);
    }

}
