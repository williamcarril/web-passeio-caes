@extends("layouts.default")

@section("title") Vacinações - {{$cao->nome}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Vacinações - {{$cao->nome}}</h1>

</section>
@endsection

@section("scripts")
@parent
@endsection
