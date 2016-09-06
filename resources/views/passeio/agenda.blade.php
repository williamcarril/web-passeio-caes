@extends("layouts.default")

@section("title") Agenda de passeios | {{config("app.name")}} @endsection

@section("main")
@include("includes.calendar")
@endsection

@section("scripts")
@endsection