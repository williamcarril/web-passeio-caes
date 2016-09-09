<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Eloquent\Imagem;
use App\Models\Eloquent\ImagemArquivo as Arquivo;

class ImagemController extends Controller {

    public function salvar($id = null, $nome = null, $descricao = null, array $arquivos = []) {
        if (!is_null($id)) {
            $imagem = Imagem::find($id);
        } else {
            $imagem = new Imagem();
        }
        $imagem->nome = $nome;
        $imagem->descricao = $descricao;
        $imagem->save();
        $imagem->arquivos()->delete();
        $errosArquivos = [];
        foreach ($arquivos as $arquivo) {
            $valores = array_merge([
                "arquivo" => null,
                "tamanho" => null
                    ], $arquivo);
            $arquivo = $this->salvarArquivo($imagem->idImagem, $valores["arquivo"], $valores["tamanho"]);
            if ($arquivo->hasErrors()) {
                $errosArquivos[] = $arquivo->getErrors();
            }
        }
        if (!empty($errosArquivos)) {
            $imagem->putErrors($errosArquivos);
        }
        return $imagem;
    }

    public function deletar($id) {
        $imagem = Imagem::find($id);
        $imagem->arquivos()->delete();
        return $imagem->delete();
    }

    public function salvarArquivo($idImagem, $nomeDoArquivo, $tamanho = null) {
        $arquivo = new Arquivo();
        $arquivo->arquivo = $nomeDoArquivo;
        $arquivo->tamanho = $tamanho;
        $arquivo->idImagem = $idImagem;
        $arquivo->save();
        return $arquivo;
    }

}
