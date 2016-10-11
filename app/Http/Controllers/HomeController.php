<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class HomeController extends Controller {
    // <editor-fold defaultstate="collapsed" desc="Rotas do passeador">
    public function route_getWalkerHome() {
        return response()->view("walker.home");
    }
    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas da área administrativa">
    public function route_getAdminHome() {
        return response()->view("admin.home");
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Rotas do site">
    public function route_getHome() {
        return response()->view("home");
    }

    // </editor-fold>
}
