@extends("walker.layouts.default", ["hasMap" => true])
@section("title") Rota completa: passeio - {{$passeio->idPasseio}} | {{config("app.name")}} Walker @endsection

@section("main")
<section>
    <h1>Rota completa: passeio - {{$passeio->idPasseio}}</h1>
    <p><b>Data:</b> {{$passeio->dataFormatada}}</p>
    <p><b>Início:</b> {{$passeio->inicioFormatado}}</p>
    <p><b>Término:</b> {{$passeio->fimFormatado}}</p>
    <p><b>Tipo:</b> {{$passeio->tipo}}</p>
    <p><b>Porte:</b> {{$passeio->porteFormatado}}</p>
    <p>
        <a class="btn btn-default" href="{{route("walker.passeio.detalhes.get", ["id" => $passeio->idPasseio])}}">
            <i class="flaticon-back"></i>
            Voltar
        </a>
    </p>
    <hr/>
    <section>
        <h2>Cães</h2>
        <div class="row">
            @foreach($caes as $cao)
            <?php
            $location = "{$cao->cliente->lat},{$cao->cliente->lng}";
            ?>
            <div class="col-lg-3 _success-color" data-role="cao" data-cliente="{{$cao->cliente->idCliente}}">
                <label class="_cursor-pointer" for="cao-{{$cao->idCao}}">
                    <img data-name="thumbnail" alt="{{$cao->nome}}" src="{{$cao->thumbnail}}"/>
                    {{$cao->nome}}
                    <input checked name='' class="hidden" id="cao-{{$cao->idCao}}" type="checkbox" value="{{$location}}">
                </label>
            </div>
            @endforeach
        </div>
    </section>
    <hr/>
    <section data-role="local">
        <input type="hidden" name="localizacaoMarcada" value="">
        <h2>Local</h2>
        <p><b>Nome: </b>{{$local->nome}}</p>
        <p><b>Endereço: </b>{{$local->getEndereco()}}</p>
        <div class="form-group">
            <label for="forma-trajeto">Forma de locomoção</label>
            <select class="form-control" id="forma-trajeto" name="locomocao">
                <option selected value="car">Carro</option>
                <option value="walking">A pé</option>
            </select>
        </div>
        <div class="form-group">
            <label for="sentido-trajeto">Objetivo</label>
            <select class="form-control" id="sentido-trajeto" name="sentido">
                <option selected value="forward">Coleta de cães para passeio</option>
                <option value="backward">Entrega de cães pós-passeio</option>
            </select>
        </div>
        <p>Marque sua localização atual no mapa para que uma rota possa ser gerada.</p>
        <p><b>Tempo estimado:</b> <span data-name="duracaoEstimada">Não definida</span></p>
        <?php
        $mapData = [
            "id" => "map",
            "callback" => "bootstrapListeners"
        ];
        ?>
        @include("includes.map", $mapData)
    </section>
</section>
@endsection

@section("templates")
@foreach($clientesConfirmados as $cliente)
@include("includes.templates.gmap-passeio-cliente-tooltip", ["cliente" => $cliente, "passeio" => $passeio, "dataTemplate" => "cliente-infowindow-{$cliente->idCliente}"]);
@endforeach
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $local = $("[data-role='local']");
        var $localizacaoMarcada = $local.find("[name='localizacaoMarcada']");
        var $duracaoEstimada = $local.find("[data-name='duracaoEstimada']")
        var $locomocao = $local.find("select[name='locomocao']");
        var $wayPoints = $("[data-role='cao']").find("input[type='checkbox']");
        var $sentido = $local.find("select[name='sentido']");

        window.bootstrapListeners = function (map, searchBox) {
            $wayPoints.on("change", function () {
                var $this = $(this);
                var $parent = $this.parents("[data-role='cao']");
                if ($wayPoints.filter(":checked").length === 0) {
                    $this.prop("checked", true);
                    return;
                }
                if ($this.prop("checked")) {
                    $parent.addClass("_success-color").removeClass("_error-color");
                } else {
                    $parent.addClass("_error-color").removeClass("_success-color");
                }
                calcularEExibirRota();
            });

            $sentido.on("change", function () {
                calcularEExibirRota();
            });

            $locomocao.on("change", function () {
                calcularEExibirRota();
            });

            var directionsService = new google.maps.DirectionsService;
            var directionsDisplay = new google.maps.DirectionsRenderer({"suppressMarkers": true});

            directionsDisplay.setMap(map);

            google.maps.event.addListener(map, "click", function (event) {
                searchBox.clear();
                clearMarkers();
                var markerData = {
                    position: event.latLng,
                    map: map,
                };
                switch ($sentido.val()) {
                    case "forward":
                        markerData.icon = "{!!asset('img/markers/walker.png')!!}";
                        break;
                    case "backward":
                        break;
                }

                map.markers.push(new google.maps.Marker(markerData));
                $localizacaoMarcada.val(event.latLng.lat() + "," + event.latLng.lng());
                calcularEExibirRota();
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
                var place = places[0];
                var markerData = {
                    map: map,
                    title: place.name,
                    position: place.geometry.location,
                    icon: "{!!asset('img/markers/walker.png')!!}"
                };
                map.markers.push(new google.maps.Marker(markerData));

                $localizacaoMarcada.val(place.geometry.location.lat() + "," + place.geometry.location.lng());
                calcularEExibirRota();
            });

            function calcularEExibirRota() {
                var travelMode = $locomocao.val();
                var localizacaoMarcada = $localizacaoMarcada.val();
                if (!localizacaoMarcada) {
                    return;
                }
                var waypts = [];
                var clientes = [];
                for (var i = 0; i < $wayPoints.length; i++) {
                    if ($wayPoints.eq(i).prop("checked")) {
                        var $cao = $wayPoints.eq(i).parents("[data-cliente]");
                        clientes.push({
                            "id": $cao.attr("data-cliente"),
                            "location": $wayPoints.eq(i).val()
                        });
                        waypts.push({
                            location: $wayPoints.eq(i).val(),
                            stopover: true
                        });
                    }
                }
                switch (travelMode) {
                    case "walking":
                        travelMode = google.maps.TravelMode.WALKING;
                        break;
                    case "car":
                    default:
                        travelMode = google.maps.TravelMode.DRIVING;
                        break;
                }
                var origem, destino;
                switch ($sentido.val()) {
                    case "forward":
                        origem = localizacaoMarcada;
                        destino = "{!!$local->lat!!},{!!$local->lng!!}";
                        break;
                    case "backward":
                        origem = "{!!$local->lat!!},{!!$local->lng!!}";
                        destino = localizacaoMarcada;
                        break;
                }
                console.log(origem);
                console.log(destino);
                directionsService.route({
                    origin: origem,
                    destination: destino,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: travelMode
                }, function (response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        clearMarkers();
                        directionsDisplay.setDirections(response);
                        var leg = response.routes[0].legs[0];
                        $duracaoEstimada.text(leg.duration.text);
                        console.log(response);
                        var startMarker, endMarker;
                        startMarker = makeMarkerWithInfowindow(
                            map,
                            {
                                "lat": parseFloat(origem.split(",")[0]),
                                "lng": parseFloat(origem.split(",")[1])
                            },
                            "<p>Você</p>",
                            "{!! asset('img/markers/walker.png') !!}"
                        );
                        var endInfowindow, endIcon;
                        switch ($sentido.val()) {
                            case "backward":
                                endInfowindow = "<p>Destino</p>";
                                endIcon = null;
                                break;
                            case "forward":
                                endInfowindow = "<p>{!! $local->nome !!}</p>";
                                endIcon = "{!!asset('img/markers/place.png')!!}";
                                break;
                        }
                        endMarker = makeMarkerWithInfowindow(
                                map,
                                {
                                    "lat": parseFloat(destino.split(",")[0]),
                                    "lng": parseFloat(destino.split(",")[1])
                                },
                                endInfowindow,
                                endIcon
                                );
                        map.markers.push(startMarker);
                        map.markers.push(endMarker);

                        for (var i = 0; i < clientes.length; i++) {
                            var location = clientes[i].location.split(",");
                            map.markers.push(makeMarkerWithInfowindow(
                                    map,
                                    {
                                        "lat": parseFloat(location[0]),
                                        "lng": parseFloat(location[1])
                                    },
                                    globals.templates.find("[data-template='cliente-infowindow-" + clientes[i].id + "']").html(),
                                    "{!!asset('img/markers/user.png')!!}"
                                    ));
                        }

                    } else {
                        showAlert("Ocorreu um erro ao carregar a rota: " + status);
                    }
                }
                );
            }

            function clearMarkers() {
                map.markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                map.markers = [];
            }
        };
    })();
</script>
@endsection