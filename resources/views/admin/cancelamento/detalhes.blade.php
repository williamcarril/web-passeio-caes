@extends("admin.layouts.default", ["hasMap" => true])
@section("title") Cancelamento - {{$cancelamento->idCancelamento}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Cancelamento - {{$cancelamento->idCancelamento}}</h1>
    <p><b>Data da solicitação:</b> {{$cancelamento->dataFormatada}}</p>
    <p><b>Hora da solicitação:</b> {{$cancelamento->horaFormatada}}</p>
    <?php
    $statusClass = "";
    switch ($cancelamento->status) {
        case $statusCancelamento["PENDENTE"]:
            $statusClass = "_warning-color";
            break;
        case $statusCancelamento["VERIFICADO"]:
            $statusClass = "";
            break;
    }
    ?>
    <p>
        <b>Status:</b> 
        <span class="{{$statusClass}}">
            {{$cancelamento->statusFormatado}}
        </span>
    </p>
    <p><b>Justificativa: </b>{{$cancelamento->justificativa}}</p>
    @if($cancelamento->status !== $statusCancelamento["VERIFICADO"])
    <button class="btn btn-success" data-action="marcar-visto">
        <i class="glyphicon glyphicon-eye-open"></i>
        Marcar como visto
    </button>
    @endif
    <hr/>
    <section>   
        <h2>Solicitante - {{$cancelamento->tipoSolicitanteFormatado}}</h2>
        @if($cancelamento->tipoPessoa === "cliente")
        <p><b>Tipo: </b> {{$cancelamento->tipoSolicitanteFormatado}}</p>
        <p><b>Nome: </b> {{$solicitante->nome}}</p>
        <p><b>Telefone: </b> {{$solicitante->telefoneFormatado}}</p>
        <p><b>E-mail: </b> {{$solicitante->email}}</p>
        @else
        <div class="row">
            <div class="col-lg-2 ">
                <img data-name="thumbnail" alt="" src="{!! $solicitante->thumbnail !!}"/>
            </div>
            <div class="col-lg-6">
                <p><b>Nome: </b> {{$solicitante->nome}}</p>
                <p><b>Telefone: </b> {{$solicitante->telefoneFormatado}}</p>
                <p><b>E-mail: </b> {{$solicitante->email}}</p>
            </div>
        </div>
        @endif
    </section>
    <hr/>
    <section>
        <h2>Passeio</h2>
        <p><b>Link para detalhes: </b> 
            <a target='_blank' href='{!!route("admin.passeio.detalhes.get", ["id" => $passeio->idPasseio])!!}'>
                {!!route("admin.passeio.detalhes.get", ["id" => $passeio->idPasseio])!!}
            </a>
        </p>
        <p><b>Data:</b> {{$passeio->dataFormatada}}</p>
        <p><b>Início:</b> {{$passeio->inicioFormatado}}</p>
        <p><b>Término:</b> {{$passeio->fimFormatado}}</p>
        <?php
        $statusClass = "";
        switch ($passeio->status) {
            case $statusPasseio["CANCELADO"]:
                $statusClass = "_error-color";
                break;
            case $statusPasseio["FEITO"]:
                $statusClass = "_success-color";
                break;
            case $statusPasseio["PENDENTE"]:
                break;
            case $statusPasseio["EM_ANDAMENTO"]:
            case $statusPasseio["EM_ANALISE"]:
                $statusClass = "_warning-color";
                break;
            default:
                if ($passeio->foiRemarcado()) {
                    $statusClass = "_warning-color";
                }
        }
        ?>
        <p><b>Status:</b> <span class="{{$statusClass}}">{{$passeio->statusFormatado}}</span></p>
        <section>
            <h3>Passeador</h3>
            <div id="passeador-wrapper" class="row">
                @if(is_null($passeador))
                <div class="col-lg-6">
                    <p class="_error-color">Não alocado</p>
                </div>
                @else
                <div data-role="passeador">
                    <div class="col-lg-2 ">
                        <img data-name="thumbnail" alt="" src="{!! $passeador->thumbnail !!}"/>
                    </div>
                    <div class="col-lg-6">
                        <p><b>Nome: </b> <span data-name="nome">{{$passeador->nome}}</span></p>
                        <p><b>Telefone: </b> <span data-name="telefone">{{$passeador->telefoneFormatado}}</span></p>
                        <p><b>E-mail: </b> <span data-name="email">{{$passeador->email}}</span></p>
                    </div>
                </div>
                @endif
            </div>
        </section>
        <section>
            <h3>Local de passeio</h3>
            <p><b>Nome: </b>{{$local->nome}}</p>
            <p><b>Endereço: </b>{{$local->getEndereco()}}</p>
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
        </section>
        <section>
            <h3>Agendamentos para este passeio</h3>
            <div class="table-responsive">
                @include("admin.includes.agendamentos-tabela", ["agendamentos" => $agendamentos])
            </div>
        </section>
    </section>

</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        $("[data-action='marcar-visto']").on("click", function () {
            $(this).defaultAjaxCall(
                    "{!! route('admin.cancelamento.visto.post', ['id' => $cancelamento->idCancelamento]) !!}",
                    "POST",
                    "{!! route('admin.cancelamento.detalhes.get', ['id' => $cancelamento->idCancelamento]) !!}"
                    );
        });
        window.decorateMap = function (map) {
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
                    @foreach($clientes as $customer)
                    var customerLatLng = new google.maps.LatLng(parseFloat("{!!$customer->lat!!}"), parseFloat("{!!$customer->lng!!}"));
            var infowindow = new google.maps.InfoWindow({
                content: ""
            });
            var marker = new google.maps.Marker({
                position: customerLatLng,
                map: map,
                icon: "{!!asset('img/markers/user.png')!!}"
            });

            marker.html = "<p>{!! $customer->nome !!}</p>";

            marker.addListener('click', function () {
                infowindow.setContent(this.html);
                infowindow.open(map, this);
            });

            map.markers.push(marker);
                    @endforeach

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