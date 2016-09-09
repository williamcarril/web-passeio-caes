@extends('layouts.default', ["hasMap" => true])

@section("title") Local - {{$local->nome}} | {{config("app.name")}} @endsection

@section("main")
<section class="clearfix">
    <h1>Local - {{$local->nome}}</h1>
    @if(!empty($imagens))
    <center>
        <div id="local-carousel" class="carousel slide -fit-content" data-ride="carousel">
            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach($imagens as $index => $imagem)
                <div class="item {{$index == 0 ? "active" : ""}}">
                    <picture>
                        <source srcset="{{$imagem->getUrl("desktop")}}" media="(min-width: 768px)" />
                        <img srcset="{{$imagem->getUrl("mobile")}}" alt="{{$imagem->nome}}" />
                    </picture>
                </div>
                @endforeach
            </div>
            @if(sizeof($imagens) > 1)
            <!-- Controls -->
            <a class="left carousel-control" href="#local-carousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Anterior</span>
            </a>
            <a class="right carousel-control" href="#local-carousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Próximo</span>
            </a>
            @endif
        </div>
    </center>
    @endif
    <p>{{$local->descricao}}</p>
    <h2>Endereço</h2>
    <?php
    $mapData = [
        "id" => "local-map",
        "searchBox" => false,
        "lat" => $local->lat,
        "lng" => $local->lng,
        "callback" => "decorateMap"
    ];
    ?>
    @include("includes.map", $mapData)
    <p>
        <b>Logradouro:</b> {{$local->logradouro}}<br/>
        <b>Bairro:</b> {{$local->bairro}}<br/>
        <b>CEP:</b> {{$local->cepFormatado}}<br/>
        @if(!empty($local->numero))
        <b>Número:</b> {{$local->numero}}<br/>
        @endif
        @if(!empty($local->complemento))
        <b>Complemento:</b> {{$local->complemento}}<br/>
        @endif
    </p>

    @if(!empty($customer) && $local->verificarServico($customer->lat, $customer->lng))
    <a class="btn btn-success pull-right" href="#">
        <i class="flaticon-calendar"></i>
        Agendar passeio
    </a>
    @endif
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        window.decorateMap = function (map, searchBox) {
            var latLng = new google.maps.LatLng(parseFloat("{!! $local->lat !!}"), parseFloat("{!! $local->lng !!}"));
            var bounds = new google.maps.LatLngBounds();
            map.markers = [];
            map.circles = [];
            map.markers.push(new google.maps.Marker({
                position: latLng,
                map: map,
                icon: "{!!asset('img/markers/place.png')!!}"
            }));

            map.circles.push(new google.maps.Circle({
                strokeColor: '#367A38',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#367A38',
                fillOpacity: 0.35,
                map: map,
                center: latLng,
                radius: parseFloat("{!!$local->raioAtuacao!!}")
            }));

            @if(!empty($customer))
                var customerLatLng = new google.maps.LatLng(parseFloat("{!!$customer->lat!!}"), parseFloat("{!!$customer->lng!!}"));
                map.markers.push(new google.maps.Marker({
                    position: customerLatLng,
                    map: map,
                    icon: "{!!asset('img/markers/user.png')!!}"
                }));
            @endif
            for (var i = 0; i < map.markers.length; i++) {
                bounds.extend(map.markers[i].getPosition());
            }
            for (var i = 0; i < map.circles.length; i++) {
                bounds.union(map.circles[i].getBounds());
            }
            map.fitBounds(bounds);
        };
    })();
</script>
@endsection