@extends('layouts.default', ["hasMap" => true])

@section("title") Local - {{$local->nome}} | {{env("APP_NAME")}} @endsection

@section("main")
<section>
    <h1>Local - {{$local->nome}}</h1>
    
</section>
@endsection
