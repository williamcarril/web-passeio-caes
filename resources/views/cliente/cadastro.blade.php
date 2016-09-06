@extends("layouts.default", ["hasMap" => true])

@section("title") Cadastro | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Cadastro - Cliente</h1>
    <form id="form-cadastro-cliente" role="form" method="POST" action="{{route("cliente.cadastro.post")}}">
        {!! csrf_field() !!}
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-nome">Nome *</label>
                <input value="{{!empty($customer->nome) ? $customer->nome : ""}}" required name="nome" id="cliente-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-cpf">CPF *</label>
                <input value="{{!empty($customer->cpf) ? $customer->cpf : ""}}" required name="cpf" data-inputmask="'mask': '999.999.999-99'" type="text" id="cliente-cpf" class="form-control" placeholder="Informe seu CPF">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-telefone">Telefone *</label>
                <input value="{{!empty($customer->telefone) ? $customer->telefone : ""}}" required name="telefone" data-inputmask="'mask': '(99) 9999[9]-9999', 'greedy': false" id="cliente-telefone" type="tel" class="form-control" placeholder="Informe seu telefone">
            </div>
        </fieldset>
        <fieldset>
            <legend>Informações de acesso</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-email">E-mail *</label>
                <input value="{{!empty($customer->email) ? $customer->email : ""}}" required name="email" id="cliente-email" type="email" class="form-control" placeholder="Informe seu e-mail">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha">Senha *</label>
                <input {{!empty($customer) ? "" : "required"}} name="senha" id="cliente-senha" type="password" class="form-control" placeholder="Informe sua senha">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha2">Confirme sua senha *</label>
                <input {{!empty($customer) ? "" : "required"}} name="senha2" id="cliente-senha2" type="password" class="form-control" placeholder="Confirme sua senha">
            </div>
        </fieldset>
        <fieldset data-name="address">
            <input value="{{!empty($customer->lat) ? $customer->lat : ""}}" required name="lat" type="hidden">
            <input value="{{!empty($customer->lng) ? $customer->lng : ""}}" required name="lng" type="hidden">
            <legend>Informações relativas ao endereço</legend>
            @include("includes.map", ["id" => "cadastro-map", "callback" => "bootstrapListeners"])
            <div class="form-group">
                <label class="control-label" for="cliente-cep">CEP *</label>
                <input {{!empty($customer) ? "" : "disabled"}} value="{{!empty($customer->postal) ? $customer->postal : ""}}" required name="postal" data-inputmask="'mask': '99999-999'" id="cliente-cep" type="text" class="form-control" placeholder="Informe seu CEP">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-logradouro">Logradouro *</label>
                <input {{!empty($customer) ? "" : "disabled"}} value="{{!empty($customer->logradouro) ? $customer->logradouro : ""}}" required name="logradouro" id="cliente-logradouro" type="text" class="form-control" placeholder="Informe seu logradouro">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-bairro">Bairro *</label>
                <input {{!empty($customer) ? "" : "disabled"}} value="{{!empty($customer->bairro) ? $customer->bairro : ""}}" required name="bairro" id="cliente-bairro" type="text" class="form-control" placeholder="Informe seu bairro">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-numero">Número *</label>
                <input {{!empty($customer) ? "" : "disabled"}} value="{{!empty($customer->numero) ? $customer->numero : ""}}" required name="numero" id="cliente-numero" type="text" class="form-control" placeholder="Informe o número de sua residência">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-complemento">Complemento</label>
                <input {{!empty($customer) ? "" : "disabled"}} value="{{!empty($customer->complemento) ? $customer->complemento : ""}}" name="complemento" id="cliente-complemento" type="text" class="form-control" placeholder="Informe o complemento do endereço (caso necessário)">
            </div>
        </fieldset>
        <button type="submit" class="btn btn-default btn-lg pull-right">{{!empty($customer) ? "Salvar" : "Cadastrar"}}</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $addressFields = $("fieldset[data-name='address'] input[disabled]");
        var $form = $("#form-cadastro-cliente");

        $form.find("input[name='nome'],input[name='logradouro'],input[name='bairro'],input[name='numero']").on("blur", function () {
            var $this = $(this);
            validate.inputs.empty($this);
        });

        $form.find("input[name='telefone']").on("blur", function () {
            var $this = $(this);
            validate.inputs.phone($this);
        });

        $form.find("input[name='postal']").on("blur", function () {
            var $this = $(this);
            validate.inputs.cep($this);
        });

        $form.find("input[name='cpf']").on("blur", function () {
            var $this = $(this);
            var cpf = $this.val();
            if (!validate.cpf(cpf)) {
                setInputStatus($this, "error");
                return;
            }
            $.ajax({
                "url": "{!! route('cliente.cadastro.check.cpf.get') !!}",
                "type": "GET",
                "data": {
                    "cpf": cpf
                },
                "beforeSend": function () {
                    $this.addClass("loading");
                },
                "success": function (response) {
                    if (response.status) {
                        setInputStatus($this, "error");
                        showAlert("O CPF informado já está sendo utilizado.", "error");
                    } else {
                        setInputStatus($this, "success");
                    }
                },
                "error": function () {
                    setInputStatus($this, "success");
                },
                "complete": function () {
                    $this.removeClass("loading");
                }
            });
        });

        $form.find("input[name='email']").on("blur", function () {
            var $this = $(this);
            var email = $this.val();
            if (!validate.email(email)) {
                setInputStatus($this, "error");
                return;
            }
            $.ajax({
                "url": "{!! route('cliente.cadastro.check.email.get') !!}",
                "type": "GET",
                "data": {
                    "email": email
                },
                "beforeSend": function () {
                    $this.addClass("loading");
                },
                "success": function (response) {
                    if (response.status) {
                        setInputStatus($this, "error");
                        showAlert("O e-mail informado já está sendo utilizado.", "error");
                    } else {
                        setInputStatus($this, "success");
                    }
                },
                "error": function () {
                    setInputStatus($this, "success");
                },
                "complete": function () {
                    $this.removeClass("loading");
                }
            });
        });

        @if (!empty($customer))
        $form.find("input[name='senha'],input[name='senha2']").on("blur", function () {
            var $senha = $form.find("input[name='senha']");
            var $senha2 = $form.find("input[name='senha2']");

            var senha = $senha.val();
            var senha2 = $senha2.val();

            if (validate.empty(senha)) {
                return;
            }
            if (validate.equals(senha, senha2)) {
                setInputStatus($senha, "success");
                setInputStatus($senha2, "success");
            } else {
                setInputStatus($senha, "error");
                setInputStatus($senha2, "error");
            }
        });
        @else
        $form.find("input[name='senha'],input[name='senha2']").on("blur", function () {
            var $senha = $form.find("input[name='senha']");
            var $senha2 = $form.find("input[name='senha2']");

            var senha = $senha.val();
            var senha2 = $senha2.val();
            if (validate.equals(senha, senha2) && !validate.empty(senha)) {
                setInputStatus($senha, "success");
                setInputStatus($senha2, "success");
            } else {
                setInputStatus($senha, "error");
                setInputStatus($senha2, "error");
            }
        });
        @endif

        $form.find("input[name='complemento']").on("blur", function () {
            setInputStatus($(this), "success");
        });

        $form.on("submit", function (ev) {
            ev.stopPropagation();
            ev.preventDefault();
            var $submit = $form.find("button[type='submit']");
            $.ajax({
                "url": $form.attr("action"),
                "type": $form.attr("method"),
                "data": $form.serialize(),
                "beforeSend": function () {
                    $submit.addClass("disabled").addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.messages, "error");
                        $submit.removeClass("disabled");
                    } else {
                        showAlert('Operação realizada com sucesso!', "success");
                        $form.find("button[type='submit']").prop("disabled", true);
                        setInterval(function () {
                            window.location.replace("{!! route('home') !!}");
                        }, 3000);
                    }
                },
                "error": function () {
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                    $submit.removeClass("disabled");
                },
                "complete": function () {
                    $submit.removeClass("loading");
                }
            });
        });

        window.bootstrapListeners = function (map, searchBox) {
//            var map = globals.maps["cadastro-map"];
//            var searchBox = globals.maps["cadastro-map"].searchBox;

            var $number = $("input[name='numero']");
            var $route = $("input[name='logradouro']");
            var $postal = $("input[name='postal']");
            var $sublocality = $("input[name='bairro']");
            var $lat = $("input[name='lat']");
            var $lng = $("input[name='lng']");

            google.maps.event.addListener(map, "click", function (event) {
                searchBox.clear();
                var geocoder = new google.maps.Geocoder;
                map.markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                map.markers = [];
                geocoder.geocode({'location': event.latLng}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        var result = results[0];
//                        var infowindow = new google.maps.InfoWindow;
                        map.markers.push(new google.maps.Marker({
                            position: event.latLng,
                            map: map,
                            icon: "{!!asset('img/markers/user.png')!!}"
                        }));
//                        infowindow.setContent(result.formatted_address);
//                        infowindow.open(map, globals.customerLocation);
                        fillAddressesFields(result, event.latLng.lat(), event.latLng.lng());
                    }
                });
            });

            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length === 0) {
                    return;
                }
                map.markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                map.markers = [];
                var bounds = new google.maps.LatLngBounds();
                var place = places[0];
                map.markers.push(new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location,
                    icon: "{!!asset('img/markers/user.png')!!}"
                }));

                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                map.fitBounds(bounds);
                fillAddressesFields(place, place.geometry.location.lat, place.geometry.location.lng);
            });

            function fillAddressesFields(result, lat, lng) {
                $addressFields.prop("disabled", false);
                $lat.val(lat);
                $lng.val(lng);
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
                if (data.number) {
                    $number.val(data.number);
                    $number.trigger("blur");
                }
                if (data.route) {
                    $route.val(data.route);
                    $route.trigger("blur");
                }
                if (data.postal) {
                    $postal.val(data.postal);
                    $postal.trigger("blur");
                }
                if (data.sublocality) {
                    $sublocality.val(data.sublocality);
                    $sublocality.trigger("blur");
                }
                if (!globals.mapWarning) {
                    showAlert("Atenção! É importante que a localização marcada no mapa esteja correta.", "warning");
                    globals.mapWarning = true;
                }
            }
        };
    })();
</script>
@endsection