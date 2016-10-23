@extends("walker.layouts.default", ["hasMap" => true])
@section("title") Passeio - {{$passeio->idPasseio}} | {{config("app.name")}} Walker @endsection

@section("main")
<section>
    <h1>Passeio - {{$passeio->idPasseio}}</h1>
    <p><b>Data:</b> {{$passeio->dataFormatada}}</p>
    <p><b>Início:</b> {{$passeio->inicioFormatado}}</p>
    <p><b>Término:</b> {{$passeio->fimFormatado}}</p>
    <p><b>Tipo:</b> {{$passeio->tipo}}</p>
    <p><b>Porte:</b> {{$passeio->porteFormatado}}</p>
    <p><b>Preço:</b> {{$passeio->getValor(null, true)}}</p>
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
    }
    ?>
    <p><b>Status:</b> <span class="{{$statusClass}}">{{$passeio->statusFormatado}}</span></p>
    <section>
        <h2>Local de passeio</h2>
        <p><b>Nome: </b>{{$local->nome}}</p>
        <p><b>Endereço: </b>{{$local->getEndereco()}}</p>
        <p>
            <a href="http://maps.google.com/maps?daddr={{$local->lat}},{{$local->lng}}" target="_blank" class="btn btn-default">
                <i class="glyphicon glyphicon-road"></i>
                Ver rota
            </a>
        </p>
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
        <h2>Cachorros</h2>
        @if($caes->count() === 0)
        <p>Não há cães confirmados para participar deste passeio.</p>
        @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Dono</th>
                        <th>Porte</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caes as $cao)
                    <?php 
                    $lat = $cao->cliente->lat;
                    $lng = $cao->cliente->lng;
                    ?>
                    <tr>
                        <td class="image">
                            <img width="100px" height="100px" src='{{$cao->thumbnail}}' />
                        </td>
                        <td data-name="nome">
                            {{$cao->nome}}
                        </td>
                        <td>{{$cao->cliente->nome}}</td>
                        <td  data-name="porte" data-value="{{$cao->porte}}">
                            {{$cao->porteFormatado}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
    @if($clientesConfirmados->count() > 0)
    <section>
        <h2>Clientes</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th>Valor a receber</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientesConfirmados as $cliente)
                    <tr>
                        <td>{{$cliente->nome}}</td>
                        <td>
                            <a href="tel:{{$cliente->telefone}}">
                                {{$cliente->telefoneFormatado}}
                            </a>
                        </td>
                        <td>{{$passeio->getValor($cliente, true)}}</td>
                        <td>
                            <a href="http://maps.google.com/maps?daddr={{$cliente->lat}},{{$cliente->lng}}" target="_blank" class="btn btn-default">
                                <i class="glyphicon glyphicon-road"></i>
                                Ver rota
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    @endif
    <hr/>
    <div class="button-group pull-right">
        <?php 
        $agora = strtotime(date("Y-m-d H:i:s"));
        $inicio = strtotime("$passeio->data $passeio->inicio");
        $fim = strtotime("$passeio->data $passeio->fim");
        ?>
        @if($inicio <= $agora && $fim >= $agora && $passeio->status === $statusPasseio["PENDENTE"])
        <button class="btn btn-success" data-action="iniciar-passeio">
            <i class="glyphicon glyphicon-ok"></i>
            Iniciar
        </button>
        @endif
        @if($passeio->status === $statusPasseio["EM_ANDAMENTO"])
        <button class="btn btn-success" data-action="finalizar-passeio">
            <i class="glyphicon glyphicon-ok"></i>
            Finalizar
        </button>
        @endif
        <a href="{{route("walker.passeio.rotas.get", ["id" => $passeio->idPasseio])}}" class="btn btn-default">
            <i class="glyphicon glyphicon-road"></i>
            Ver rota completa
        </a>
        <?php
        $podeSerCancelado = $passeio->status !== $statusPasseio["CANCELADO"] && $passeio->status !== $statusPasseio["FEITO"];
        ?>
        @if($podeSerCancelado)
        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelamento-modal">
            <i class="glyphicon glyphicon-remove"></i>
            Cancelar
        </a>
        @endif
    </div>
    <div id="cancelamento-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <section class="modal-content">
                <header class="modal-header">
                    <button data-role="cancel-button" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h1 class="modal-title" data-role="title">Cancelar agendamento</h1>
                </header>
                <div class="modal-body">
                    <p>Caso tenha certeza do cancelamento do passeio, por favor, informe-nos o motivo para tal:</p>
                    <textarea class="form-control" name="motivo"></textarea>
                </div>
                <footer class="modal-footer">
                    <button data-role="confirm-button" type="button" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </footer>
            </section>
        </div>
    </div>
</section>
@endsection

@section("templates")
    @foreach($clientesConfirmados as $cliente)
    @include("includes.templates.gmap-passeio-cliente-tooltip", ["cliente" => $cliente, "passeio" => $passeio]);
    @endforeach
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $modalCancelamento = $("#cancelamento-modal");

        $modalCancelamento.on("modal.bs.hidden", function () {
            $modalCancelamento.find("textarea").val("");
        });

        $modalCancelamento.find("[data-role='confirm-button']").click(function (ev) {
            ev.preventDefault();
            ev.stopPropagation();
            var motivo = $modalCancelamento.find("textarea[name='motivo']").val();

            if (!motivo.trim()) {
                showAlert("Por favor, informe o motivo do cancelamento.");
                return;
            }
            $(this).defaultAjaxCall(
                    "{!! route('walker.passeio.cancelar.post', ['id' => $passeio->idPasseio]) !!}",
                    "POST",
                    "{!! route('walker.passeio.confirmado.listagem.get') !!}",
                    {
                        "motivo": motivo
                    });
        });
        
        $("[data-action='iniciar-passeio']").click(function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            $(this).defaultAjaxCall(
                    "{!! route('walker.passeio.iniciar.post', ['id' => $passeio->idPasseio]) !!}",
                    "POST",
                    "{!! route('walker.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}"
            );
        });
        $("[data-action='finalizar-passeio']").click(function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            $(this).defaultAjaxCall(
                    "{!! route('walker.passeio.finalizar.post', ['id' => $passeio->idPasseio]) !!}",
                    "POST",
                    "{!! route('walker.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}"
            );
        });
        
        window.decorateMap = function (map) {
            var latLng = new google.maps.LatLng(parseFloat("{!! $local->lat !!}"), parseFloat("{!! $local->lng !!}"));
            var bounds = new google.maps.LatLngBounds();
            map.markers = [];
            map.circles = [];
            
            var localMarker = makeMarkerWithInfowindow(map,latLng, "<p>{!! $local->nome !!}</p>", "{!!asset('img/markers/place.png')!!}");
            map.markers.push(localMarker);
            
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
            
            @foreach($clientesConfirmados as $customer)
                var customerLatLng = new google.maps.LatLng(parseFloat("{!!$customer->lat!!}"), parseFloat("{!!$customer->lng!!}"));
                var html = globals.templates.find("[data-template='cliente-tooltip-{!!$customer->idCliente!!}']").html();
                map.markers.push(makeMarkerWithInfowindow(
                    map, 
                    customerLatLng, 
                    html, 
                    "{!!asset('img/markers/user.png')!!}")
                );
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