<?php
$idPrefix = isset($idPrefix) ? $idPrefix : uniqid("address");
if (isset($values)) {
    if (is_array($values)) {
        $values = (object) $values;
    }
} else {
    $values = null;
}

$defaultFields = ["cep", "logradouro", "bairro", "numero", "complemento", "lat", "lng"];
if (isset($fields)) {
    $fields = array_merge($defaultFields, $fields);
} else {
    $fields = $defaultFields;
}

$defaultNames = [
    "cep" => "postal",
    "logradouro" => "logradouro",
    "bairro" => "bairro",
    "numero" => "numero",
    "complemento" => "complemento",
    "lat" => "lat",
    "lng" => "lng"
];
if (isset($names)) {
    $names = array_merge($defaultNames, $names);
} else {
    $names = $defaultNames;
}

$defaultRequired = [
    "cep" => true,
    "logradouro" => true,
    "bairro" => true,
    "numero" => true,
    "complemento" => false,
    "lat" => true,
    "lng" => true
];
if (isset($required)) {
    $required = array_merge($defaultRequired, $required);
} else {
    $required = $defaultRequired;
}
$possessivePlaceholders = isset($possessivePlaceholders) ? $possessivePlaceholders : false;
$placeholders = [
    "possessive" => [
        "cep" => "Informe seu CEP",
        "logradouro" => "Informe seu logradouro",
        "bairro" => "Informe seu bairro",
        "numero" => "Informe o número de sua residência",
        "complemento" => "Informe o complemento de seu endereço (caso necessário)"
    ],
    "neutral" => [
        "cep" => "Informe o CEP",
        "logradouro" => "Informe o logradouro",
        "bairro" => "Informe o bairro",
        "numero" => "Informe o número do local",
        "complemento" => "Informe o complemento do endereço (caso necessário)"
    ]
];
if ($possessivePlaceholders) {
    $placeholders = $placeholders["possessive"];
} else {
    $placeholders = $placeholders["neutral"];
}
$customMarker = isset($customMarker) ? $customMarker : null;
$alertOnPin = isset($alertOnPin) ? $alertOnPin : false;
$tabIndex = isset($tabIndex) ? $tabIndex : null;

$prepend = isset($prepend) ? $prepend : null;
$append = isset($append) ? $append : null;
$postPrependTabIndex = isset($postPrependTabIndex) ? $postPrependTabIndex : $tabIndex;
$postMapLoad = isset($postMapLoad) ? $postMapLoad : null;
?>
<fieldset data-name="address" id="{{$idPrefix}}-fieldset">
    @if(in_array("lat", $fields))
    <input value="{{!empty($values->lat) ? $values->lat : ""}}" {{$required["lat"] ? "required" : ""}} name="{{$names['lat']}}" type="hidden">
    @endif

    @if(in_array("lng", $fields))
    <input value="{{!empty($values->lng) ? $values->lng : ""}}" {{$required["lat"] ? "required" : ""}} name="{{$names['lng']}}" type="hidden">
    @endif
    <legend>Informações relativas ao endereço</legend>
    <?php 
    $mapData = [
        "id" => "$idPrefix-map", 
        "callback" => "{$idPrefix}_bootstrapListeners",
        "tabIndex" => !is_null($tabIndex) ? post_increment($tabIndex) : null
    ];
    if(!empty($values["lat"] && !empty($values["lng"]))) {
        $mapData["lat"] = $values["lat"];
        $mapData["lng"] = $values["lng"];
    }
    ?>
    @include("includes.map", $mapData)
    @if(!empty($prepend))
    <?php 
        $tabIndex = $postPrependTabIndex;
    ?>
    {!!$prepend!!}
    @endif
    @if(in_array("cep", $fields))
    <div class="form-group">
        <label class="control-label" for="{{$idPrefix}}-cep">CEP{{$required["cep"] ? " *" : ""}}</label>
        <input {{!is_null($tabIndex) ? "tabindex=" . post_increment($tabIndex) : ""}} {{!empty($values) ? "" : "disabled"}} value="{{!empty($values->postal) ? $values->postal : ""}}" {{$required["cep"] ? "required" : ""}} name="{{$names["cep"]}}" data-inputmask="'mask': '99999-999'" id="{{$idPrefix}}-cep" type="text" class="form-control" placeholder="{{$placeholders["cep"]}}">
    </div>
    @endif
    @if(in_array("logradouro", $fields))
    <div class="form-group">
        <label class="control-label" for="{{$idPrefix}}-logradouro">Logradouro{{$required["logradouro"] ? " *" : ""}}</label>
        <input {{!is_null($tabIndex) ? "tabindex=" . post_increment($tabIndex) : ""}} {{!empty($values) ? "" : "disabled"}} value="{{!empty($values->logradouro) ? $values->logradouro : ""}}" {{$required["logradouro"] ? "required" : ""}} name="{{$names["logradouro"]}}" id="{{$idPrefix}}-logradouro" type="text" class="form-control" placeholder="{{$placeholders["logradouro"]}}"/>
    </div>
    @endif
    @if(in_array("bairro", $fields))
    <div class="form-group">
        <label class="control-label" for="{{$idPrefix}}-bairro">Bairro{{$required["bairro"] ? " *" : ""}}</label>
        <input {{!is_null($tabIndex) ? "tabindex=" . post_increment($tabIndex) : ""}} {{!empty($values) ? "" : "disabled"}} value="{{!empty($values->bairro) ? $values->bairro : ""}}" {{$required["bairro"] ? "required" : ""}} name="{{$names["bairro"]}}" id="{{$idPrefix}}-bairro" type="text" class="form-control" placeholder="{{$placeholders["bairro"]}}"/>
    </div>
    @endif
    @if(in_array("numero", $fields))
    <div class="form-group">
        <label class="control-label" for="{{$idPrefix}}-numero">Número{{$required["numero"] ? " *" : ""}}</label>
        <input {{!is_null($tabIndex) ? "tabindex=" . post_increment($tabIndex) : ""}} {{!empty($values) ? "" : "disabled"}} value="{{!empty($values->numero) ? $values->numero : ""}}" {{$required["numero"] ? "required" : ""}} name="{{$names["numero"]}}" id="{{$idPrefix}}-numero" type="text" class="form-control" placeholder="{{$placeholders["numero"]}}"/>
    </div>
    @endif
    @if(in_array("complemento", $fields))
    <div class="form-group">
        <label class="control-label" for="{{$idPrefix}}-complemento">Complemento{{$required["complemento"] ? " *" : ""}}</label>
        <input {{!is_null($tabIndex) ? "tabindex=" . post_increment($tabIndex) : ""}} {{!empty($values) ? "" : "disabled"}} value="{{!empty($values->complemento) ? $values->complemento : ""}}" {{$required["complemento"] ? "required" : ""}} name="complemento" id="{{$idPrefix}}-complemento" type="text" class="form-control" placeholder="{{$placeholders["complemento"]}}"/>
    </div>
    @endif
    @if(!empty($append))
    {!!$append!!}
    @endif
</fieldset>

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $disabledAddressFields = $("fieldset[data-name='address'] input[disabled],textarea[disabled],select[disabled]");
        var $fieldset = $("#{!!$idPrefix!!}-fieldset");
        @foreach($required as $key => $required)
            @if($key === "cep")
                <?php continue; ?>
            @endif
            $fieldset.find("input[name='{!!$names[$key]!!}'],textarea[name='{!!$names[$key]!!}'],select[name='{!!$names[$key]!!}']").on("blur", function () {
                @if($required)
                    $(this).validate("empty")
                @else
                    setInputStatus($(this), "success");
                @endif
            });
        @endforeach
        $fieldset.find("input[name='{!!$names['cep']!!}']").on("blur", function () {
            $(this).validate("cep");
        });
        
        window["{!!$idPrefix!!}_bootstrapListeners"] = function (map, searchBox) {
            var $number = $("input[name='{!!$names['numero']!!}']");
            var $route = $("input[name='{!!$names['logradouro']!!}']");
            var $postal = $("input[name='{!!$names['cep']!!}']");
            var $sublocality = $("input[name='{!!$names['bairro']!!}']");
            var $lat = $("input[name='{!!$names['lat']!!}']");
            var $lng = $("input[name='{!!$names['lng']!!}']");
            
            if($lat.val() && $lng.val()) {
                map.markers = [];
                var markerData = {
                    position: {"lat": parseFloat($lat.val()), "lng": parseFloat($lng.val())},
                    map: map
                };
                @if(!empty($customMarker))
                    markerData.icon = "{!!$customMarker!!}";
                @endif
                map.markers.push(new google.maps.Marker(markerData));
            }
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
                        var markerData = {
                            position: event.latLng,
                            map: map
                        };
                        @if(!empty($customMarker))
                            markerData.icon = "{!!$customMarker!!}";
                        @endif
//                        var infowindow = new google.maps.InfoWindow;
                        map.markers.push(new google.maps.Marker(markerData));
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
                var markerData = {
                    map: map,
                    title: place.name,
                    position: place.geometry.location
                };
                @if(!empty($customMarker))
                    markerData.icon = "{!!$customMarker!!}";
                @endif
                map.markers.push(new google.maps.Marker(markerData));
                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                map.fitBounds(bounds);
                fillAddressesFields(place, place.geometry.location.lat, place.geometry.location.lng);
            });
            function fillAddressesFields(result, lat, lng) {
                $disabledAddressFields.prop("disabled", false);
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
                if (!globals["{!!$idPrefix!!}_mapWarning"]) {
                    showAlert("Atenção! É importante que a localização marcada no mapa esteja correta.", "warning");
                    globals["{!!$idPrefix!!}_mapWarning"] = true;
                }
            }
            @if(!empty($postMapLoad))
            window["{!!$postMapLoad!!}"]();
            @endif
        };
    })();
</script>
@endsection