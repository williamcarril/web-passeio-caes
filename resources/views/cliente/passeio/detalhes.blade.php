@extends("layouts.default", ["hasMap" => true])
@section("title") Passeio - {{$passeio->idPasseio}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Passeio - {{$passeio->idPasseio}}</h1>
    <p><b>Data:</b> {{$passeio->dataFormatada}}</p>
    <p><b>Início:</b> {{$passeio->inicioFormatado}}</p>
    <p><b>Término:</b> {{$passeio->fimFormatado}}</p>
    <p><b>Tipo:</b> {{$passeio->tipo}}</p>
    <p><b>Porte:</b> {{$passeio->porteFormatado}}</p>
    <p><b>Preço:</b> {{$passeio->getValor($customer, true)}}</p>
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
        <h2>Local de passeio</h2>
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
        <h2>Passeador</h2>
        @if(is_null($passeador))
        <p>Não alocado</p>
        @else
        <div class="row">
            <div class="col-lg-2 ">
                <img data-name="thumbnail" alt="" src="{!! $passeador->thumbnail !!}"/>
            </div>
            <div class="col-lg-6">
                <p><b>Nome: </b> <span data-name="nome">{{$passeador->nome}}</span></p>
                <p><b>Telefone: </b> <span data-name="telefone">{{$passeador->telefoneFormatado}}</span></p>
            </div>
        </div>
        @endif
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
                        <th>Porte</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caes as $cao)
                    <tr class="{{$cao->idCliente === $customer->idCliente ? "_success-color" : ""}}">
                        <td>
                            <img width="100px" height="100px" src='{{$cao->thumbnail}}' />
                        </td>
                        <td data-name="nome">
                            {{$cao->nome}}
                        </td>
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
    <hr/>
    <div class="button-group pull-right">
        <?php
        $podeSerCancelado = $passeio->status !== $statusPasseio["CANCELADO"] && $passeio->status !== $statusPasseio["FEITO"] && $passeio->getCaesConfirmadosDoCliente($customer)->count() > 0
        ?>
        @if($podeSerCancelado)
        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelamento-modal">
            <i class="glyphicon glyphicon-remove"></i>
            Cancelar
        </a>
        @endif
        @if($passeio->status === $statusPasseio["EM_ANDAMENTO"])
        <a class="btn btn-default" href="#">
            <i class="flaticon-walker"></i>
            Localizar passeador
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
                    "{!! route('cliente.passeio.cancelar.post', ['id' => $passeio->idPasseio]) !!}",
                    "POST",
                    "{!! route('cliente.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}",
                    {
                        "motivo": motivo
                    });
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