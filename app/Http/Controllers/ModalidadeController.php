<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Modalidade;

class ModalidadeController extends Controller {

    //
    // <editor-fold defaultstate="collapsed" desc="Rotas GET que retornam JSON">
    public function route_getModalidadeJson(Request $req, $id) {
        $modalidade = Modalidade::find($id);
        $arr = $modalidade->toArray();
        $arr["tipo"] = $modalidade->tipoFormatado;
        $arr["periodo"] = $modalidade->periodoFormatado;
        $arr["frequencia"] = $modalidade->frequenciaFormatada;
        $arr["pacote"] = $modalidade->tipo === "pacote" ? true : false;
        return $this->defaultJsonResponse(true, null, $arr);
    }

    // </editor-fold>
}
