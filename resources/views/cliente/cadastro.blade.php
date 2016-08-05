@extends("layouts.default", ["hasMap" => true])

@section("title") Cadastro | {{env("APP_NAME")}} @endsection

@section("main")
<h1>Cadastro - Cliente</h1>
<form id="form-cadastro-cliente" role="form">
    {!! csrf_field() !!}
    <fieldset>
        <legend>Informações gerais</legend>
        <div class="form-group">
            <label class="control-label" for="cliente-nome">Nome</label>
            <input required name="nome" id="cliente-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-cpf">CPF</label>
            <input required name="cpf" data-inputmask="'mask': '999.999.999-99'" type="text" pattern="[0-9]*" inputmode="numeric" id="cliente-cpf" class="form-control" placeholder="Informe seu CPF">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-telefone">Telefone</label>
            <input required name="telefone" data-inputmask="'mask': '(99) 9999[9]-9999', 'greedy': false" id="cliente-telefone" type="tel" class="form-control" placeholder="Informe seu telefone">
        </div>

    </fieldset>
    <fieldset>
        <legend>Informações de acesso</legend>
        <div class="form-group">
            <label class="control-label" for="cliente-email">E-mail</label>
            <input required name="email" id="cliente-email" type="email" class="form-control" placeholder="Informe seu e-mail">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-senha">Senha</label>
            <input required name="senha" id="cliente-senha" type="password" class="form-control" placeholder="Informe sua senha">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-senha2">Confirme sua senha</label>
            <input required name="senha2" id="cliente-senha2" type="password" class="form-control" placeholder="Confirme sua senha">
        </div>
    </fieldset>
    <fieldset>
        <input required name="lat" type="hidden">
        <input required name="lng" type="hidden">
        <legend>Informações relativas ao endereço</legend>
        @include("includes.map", ["id" => "cadastro-map", "callback" => "bootstrapListeners"])
        <div class="form-group">
            <label class="control-label" for="cliente-cep">CEP</label>
            <input required name="postal" data-inputmask="'mask': '99999-999'" pattern="[0-9]*" inputmode="numeric" id="cliente-cep" type="text" class="form-control" placeholder="Informe seu CEP">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-logradouro">Logradouro</label>
            <input required name="logradouro" id="cliente-logradouro" type="text" class="form-control" placeholder="Informe seu logradouro">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-bairro">Bairro</label>
            <input required name="bairro" id="cliente-bairro" type="text" class="form-control" placeholder="Informe seu bairro">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-numero">Número</label>
            <input required name="numero" id="cliente-numero" type="text" class="form-control" placeholder="Informe o número de sua residência">
        </div>
        <div class="form-group">
            <label class="control-label" for="cliente-complemento">Complemento</label>
            <input name="complemento" id="cliente-complemento" type="text" class="form-control" placeholder="Informe o complemento do endereço (caso necessário)">
        </div>
    </fieldset>
    <button type="submit" class="btn btn-default">Cadastrar</button>
</form>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        showAlert("teste");
        window.bootstrapListeners = function () {
            var map = globals.maps["cadastro-map"];

            google.maps.event.addListener(map, "click", function (event) {
                var geocoder = new google.maps.Geocoder;
                if (globals.customerLocation) {
                    globals.customerLocation.setMap(null);
                }
                geocoder.geocode({'location': event.latLng}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        var result = results[0];
                        var infowindow = new google.maps.InfoWindow;
                        globals.customerLocation = new google.maps.Marker({
                            position: event.latLng,
                            map: map
                        });
                        infowindow.setContent(result.formatted_address);
                        infowindow.open(map, globals.customerLocation);
                    } else {
                        
                    }
                });
            });
        };
    })();
</script>
@endsection