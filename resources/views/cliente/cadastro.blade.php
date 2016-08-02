@extends("layouts.default", ["hasMap" => true])

@section("title") Cadastro | {{env("APP_NAME")}} @endsection

@section("main")
<h1>Cadastro - Cliente</h1>
<form  id="form-cadastro-cliente" role="form">
    {!! csrf_field() !!}
    "rua",
    "bairro",
    "postal",
    "numero",
    "lat",
    "lng",
    "email",
    "senha",
    <fieldset>
        <legend>Informações de contato</legend>
        <div class="form-group">
            <label class="control-label" for="cliente-nome">Nome</label>
            <input id="cliente-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-cpf">CPF</label>
            <input id="cliente-cpf" type="number" class="form-control" placeholder="Informe seu CPF">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-email">E-mail</label>
            <input id="cliente-email" type="email" class="form-control" placeholder="Informe seu e-mail">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-telefone">Telefone</label>
            <input id="cliente-telefone" type="text" class="form-control" placeholder="Informe seu telefone">
        </div>
    </fieldset>
    <fieldset>
        <legend>Endereço</legend>
        @include("includes.map", ["id" => "cadastro-map"])
    </fieldset>
    <button type="submit" class="btn btn-default">Cadastrar</button>
</form>
@endsection
