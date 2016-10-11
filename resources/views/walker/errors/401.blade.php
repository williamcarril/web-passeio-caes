@extends("walker.layouts.errors", ["link" => route("walker.login.get"), "linkMessage" => "Vá para a página de login"])
@section("title") Falha de autenticação @endsection
@section("message")
<p>
    Você precisa estar autenticado no sistema para acessar esta página.
</p>
@endsection