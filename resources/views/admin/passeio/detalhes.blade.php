@extends("admin.layouts.default", ["hasMap" => true])
@section("title") Passeio - {{$passeio->idPasseio}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Passeio - {{$passeio->idPasseio}}</h1>
    <p><b>Data:</b> {{$passeio->dataFormatada}}</p>
    <p><b>Início:</b> {{$passeio->inicioFormatado}}</p>
    <p><b>Término:</b> {{$passeio->fimFormatado}}</p>
    <p><b>Tipo:</b> {{$passeio->tipo}}</p>
    <p><b>Porte:</b> {{$passeio->porteFormatado}}</p>
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
            if($passeio->foiRemarcado()) {
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
        <h2>Cachorros</h2>
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
                    <tr>
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
    </section>
    <section>
        <h2>Agendamentos para este passeio</h2>
        <div class="table-responsive">
            <table id="agendamento-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data do agendamento</th>
                        <th>Hora do agendamento</th>
                        <th>Modalidade</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendamentos as $agendamento)
                    <?php
                    $statusClass = "";
                    switch ($agendamento->status) {
                        case $statusAgendamento["CANCELADO"]:
                            $statusClass = "_error-color";
                            break;
                        case $statusAgendamento["FEITO"]:
                            $statusClass = "";
                            break;
                        case $statusAgendamento["CLIENTE"]:
                        case $statusAgendamento["FUNCIONARIO"]:
                            $statusClass = "_warning-color";
                            break;
                    }
                    ?>
                    <tr class="{{$statusClass}}">
                        <td>{{$agendamento->idAgendamento}}</td>
                        <td>{{$agendamento->cliente->nome}}</td>
                        <td>{{$agendamento->dataFormatada}}</td>
                        <td>{{$agendamento->horaFormatada}}</td>
                        <td>{{$agendamento->modalidade->nome}}</td>
                        <td>{{$agendamento->precoTotalFormatado}}</td>
                        <td>{{$agendamento->statusFormatado}}</td>
                        <td>
                            <div class="button-group">
                                <a href="{{route('admin.agendamento.detalhes.get', ["id" => $agendamento->idAgendamento])}}" class="btn btn-default">
                                    <i class="glyphicon glyphicon-search"></i>
                                    Detalhes
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <hr/>
    <div class="button-group pull-right">
        @if($passeio->status !== $statusPasseio["CANCELADO"] && $passeio->status !== $statusPasseio["FEITO"])
        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelamento-modal">Cancelar passeio</a>
        @endif
        @if($passeio->status === $statusPasseio["PENDENTE"] && strtotime(date("Y-m-d")) > strtotime($passeio->data))
        <button class="btn btn-warning" data-action="marcar-feito">Marcar como feito</button>
        @endif
        @if($agendamento->status === $statusPasseio["EM_ANALISE"])
        <button class="btn btn-success" data-action="aceitar-passeio">Aceitar passeio</button>
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
                    <button data-role="confirm-cancel-button" type="button" class="btn btn-success">Confirmar</button>
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
    var $modal = $("#cancelamento-modal");
    $("[data-action='marcar-feito']").on("click", function(ev) {
        askConfirmation("Conclusão de passeio", "Tem certeza que deseja marcar este passeio como feito?", function () {
                $(this).defaultAjaxCall(
                        "{!! route('admin.passeio.status.feito.post', ['id' => $passeio->idPasseio]) !!}",
                        "POST",
                        "{!! route('admin.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}"
                        );
            });
    });
    
    $modal.on("modal.bs.hidden", function () {
        $modal.find("textarea").val("");
    });

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
        
        marker.addListener('click', function() {
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
    }
})();
</script>
@endsection