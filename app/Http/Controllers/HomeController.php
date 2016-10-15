<?php

namespace App\Http\Controllers;

class HomeController extends Controller {

    // <editor-fold defaultstate="collapsed" desc="Rotas do passeador">
    public function route_getWalkerHome() {
        $data = [
        ];
        return response()->view("walker.home", $data);
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
