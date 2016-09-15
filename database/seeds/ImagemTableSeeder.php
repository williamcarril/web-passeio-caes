<?php

use Illuminate\Database\Seeder;
use App\Models\File\Repositorio;

class ImagemTableSeeder extends Seeder {

    private $repositorio;

    public function __construct(Repositorio $repositorio) {
        $this->repositorio = $repositorio;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \DB::table("imagem")->insert([
            [
                "idImagem" => 1,
                "data" => date("Y-m-d"),
                "nome" => "Administrator"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/administrator.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 1,
                "idImagem" => 1,
                "tamanho" => null,
                "arquivo" => $file
            ]
        ]);

        \DB::table("imagem")->insert([
            [
                "idImagem" => 2,
                "data" => date("Y-m-d"),
                "nome" => "Cão"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/dog.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 2,
                "idImagem" => 2,
                "tamanho" => null,
                "arquivo" => $file
            ]
        ]);

        \DB::table("imagem")->insert([
            [
                "idImagem" => 3,
                "data" => date("Y-m-d"),
                "nome" => "Ibirapuera"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/ibirapuera-big.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 3,
                "idImagem" => 3,
                "tamanho" => "desktop",
                "arquivo" => $file
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/ibirapuera-small.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 4,
                "idImagem" => 3,
                "tamanho" => "mobile",
                "arquivo" => $file
            ]
        ]);

        \DB::table("imagem")->insert([
            [
                "idImagem" => 4,
                "data" => date("Y-m-d"),
                "nome" => "V_Administrator"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/v_administrator.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 5,
                "idImagem" => 4,
                "tamanho" => null,
                "arquivo" => $file
            ]
        ]);
        
        \DB::table("imagem")->insert([
            [
                "idImagem" => 5,
                "data" => date("Y-m-d"),
                "nome" => "Chico Mendes"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/chico-mendes_big.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 6,
                "idImagem" => 5,
                "tamanho" => "desktop",
                "arquivo" => $file
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/chico-mendes_small.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 7,
                "idImagem" => 5,
                "tamanho" => "mobile",
                "arquivo" => $file
            ]
        ]);
        
        \DB::table("imagem")->insert([
            [
                "idImagem" => 6,
                "data" => date("Y-m-d"),
                "nome" => "Cão Grande"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/dog_big.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 8,
                "idImagem" => 6,
                "tamanho" => null,
                "arquivo" => $file
            ]
        ]);
        
        \DB::table("imagem")->insert([
            [
                "idImagem" => 7,
                "data" => date("Y-m-d"),
                "nome" => "Cão Pequeno"
            ]
        ]);
        $file = $this->repositorio->copy(public_path("img/mock/dog_small.png"));
        \DB::table("imagem_arquivo")->insert([
            [
                "idImagemArquivo" => 9,
                "idImagem" => 7,
                "tamanho" => null,
                "arquivo" => $file
            ]
        ]);
    }

}
