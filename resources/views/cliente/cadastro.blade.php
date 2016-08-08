@extends("layouts.default", ["hasMap" => true])

@section("title") Cadastro | {{env("APP_NAME")}} @endsection

@section("main")
<section>

    <h1>Cadastro - Cliente</h1>
    <form id="form-cadastro-cliente" role="form">
        {!! csrf_field() !!}
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-nome">Nome *</label>
                <input required name="nome" id="cliente-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-cpf">CPF *</label>
                <input required name="cpf" data-inputmask="'mask': '999.999.999-99'" type="text" id="cliente-cpf" class="form-control" placeholder="Informe seu CPF">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-telefone">Telefone *</label>
                <input required name="telefone" data-inputmask="'mask': '(99) 9999[9]-9999', 'greedy': false" id="cliente-telefone" type="tel" class="form-control" placeholder="Informe seu telefone">
            </div>

        </fieldset>
        <fieldset>
            <legend>Informações de acesso</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-email">E-mail *</label>
                <input required name="email" id="cliente-email" type="email" class="form-control" placeholder="Informe seu e-mail">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha">Senha *</label>
                <input required name="senha" id="cliente-senha" type="password" class="form-control" placeholder="Informe sua senha">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha2">Confirme sua senha *</label>
                <input required name="senha2" id="cliente-senha2" type="password" class="form-control" placeholder="Confirme sua senha">
            </div>
        </fieldset>
        <fieldset data-name="address">
            <input required name="lat" type="hidden">
            <input required name="lng" type="hidden">
            <legend>Informações relativas ao endereço</legend>
            @include("includes.map", ["id" => "cadastro-map", "callback" => "bootstrapListeners"])
            <div class="form-group">
                <label class="control-label" for="cliente-cep">CEP *</label>
                <input disabled required name="postal" data-inputmask="'mask': '99999-999'" id="cliente-cep" type="text" class="form-control" placeholder="Informe seu CEP">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-logradouro">Logradouro *</label>
                <input disabled required name="logradouro" id="cliente-logradouro" type="text" class="form-control" placeholder="Informe seu logradouro">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-bairro">Bairro *</label>
                <input disabled required name="bairro" id="cliente-bairro" type="text" class="form-control" placeholder="Informe seu bairro">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-numero">Número *</label>
                <input disabled required name="numero" id="cliente-numero" type="text" class="form-control" placeholder="Informe o número de sua residência">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-complemento">Complemento</label>
                <input disabled name="complemento" id="cliente-complemento" type="text" class="form-control" placeholder="Informe o complemento do endereço (caso necessário)">
            </div>
        </fieldset>
        <button type="submit" class="btn btn-default">Cadastrar</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $addressFields = $("fieldset[data-name='address'] input[disabled]");

        window.bootstrapListeners = function () {
            var map = globals.maps["cadastro-map"];

            var $number = $("input[name='numero']");
            var $route = $("input[name='logradouro']");
            var $postal = $("input[name='postal']");
            var $sublocality = $("input[name='bairro']");
            var $lat = $("input[name='lat']");
            var $lng = $("input[name='lat']");
            
            google.maps.event.addListener(map, "click", function (event) {
                var geocoder = new google.maps.Geocoder;
                if (globals.customerLocation) {
                    globals.customerLocation.setMap(null);
                }
                geocoder.geocode({'location': event.latLng}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        var result = results[0];
//                        var infowindow = new google.maps.InfoWindow;
                        globals.customerLocation = new google.maps.Marker({
                            position: event.latLng,
                            map: map
                        });
//                        infowindow.setContent(result.formatted_address);
//                        infowindow.open(map, globals.customerLocation);
                        $addressFields.prop("disabled", false);
                        $lat.val(event.latLng.lat());
                        $lng.val(event.latLng.lng());
                        fillAddressesFields(result);
                        if(!globals.mapWarning) {
                            showAlert("Atenção! É importante que a localização marcada no mapa esteja correta.", "warning");
                            globals.mapWarning = true;
                        }
                    }
                });
            });

            function fillAddressesFields(result) {
                var data = {
                    "number": null,
                    "route": null,
                    "sublocality": null,
                    "postal_code": null
                };
                var components = result.address_components;
                for (var i in components) {
                    var component = components[i];
                    var types = component.types;
                    if (types.indexOf("street_number") !== -1 && !data.number) {
                        data.number = component.long_name;
                        continue;
                    }
                    if (types.indexOf("route") !== -1 && !data.route) {
                        data.route = component.long_name;
                        continue;
                    }
//                    if(types.contains("administrative_area_level_2") && !data.city) {
//                        data.city = component.long_name;
//                    }
//                    if(types.contains("administrative_area_level_1") && !data.state) {
//                        data.state = component.long_name;
//                    }
                    if (types.indexOf("postal_code") !== -1 && !data.postal) {
                        data.postal = component.long_name;
                        continue;
                    }
                    if (types.indexOf("sublocality_level_1") !== -1 && !data.sublocality) {
                        data.sublocality = component.long_name;
                        continue;
                    }
                }
                if (!$number.val() && data.number) {
                    $number.val(data.number);
                }
                if (!$route.val() && data.route) {
                    $route.val(data.route);
                }
                if (!$postal.val() && data.postal) {
                    $postal.val(data.postal);
                }
                if (!$sublocality.val() && data.sublocality) {
                    $sublocality.val(data.sublocality);
                }
            }
        };
    })();
</script>
@endsection