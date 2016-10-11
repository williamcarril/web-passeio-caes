@extends('walker.layouts.default')

@section("title") Locais | {{config("app.name")}} Walker @endsection

@section("main")
<section>
    <h1>Locais</h1>
    <div class="row">
        @foreach($locais as $local)
        <div class="col-sm-8 col-lg-4">
            <?php
            $data = [
                "thumbnail" => $local->thumbnail,
                "name" => $local->nome,
                "description" => $local->descricao,
                "address" => $local->getEndereco(["logradouro", "bairro", "numero"]),
                "lat" => $local->lat,
                "lng" => $local->lng,
                "link" => route("walker.local.detalhes.get", ["id" => $local->idLocal])
            ];
            ?>
            @include("local.includes.place-box", $data)
        </div>
        @endforeach
    </div>
</section>
@endsection
