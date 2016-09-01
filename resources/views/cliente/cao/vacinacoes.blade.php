@extends("layouts.default")

@section("title") Vacinações - {{$cao->nome}} | {{env("APP_NAME")}} @endsection

@section("main")
<section>
    <h1>Vacinações - {{$cao->nome}}</h1>

</section>
@endsection

@section("scripts")
@parent
@endsection
