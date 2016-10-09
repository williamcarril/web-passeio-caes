@extends("layouts.default", ["hasMap" => true])
@section("title") Agendamento - {{$agendamento->idAgendamento}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Agendamento - {{$agendamento->idAgendamento}}</h1>
    <p><b>Data da solicitação:</b> {{$agendamento->dataFormatada}}</p>
    <p><b>Hora da solicitação:</b> {{$agendamento->horaFormatada}}</p>
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
    <p><b>Status:</b> 
        <span class="{{$statusClass}}">
            {{$agendamento->statusFormatado}}
        </span>
    </p>
    @if($agendamento->foiReagendado())
    <p>Para ver o agendamento novo, clique <a href="{{route("cliente.agendamento.detalhes.get", ["id" => $agendamento->idAgendamentoNovo])}}">aqui</a>.</p>
    @endif
    <section>
        <h2>Modalidade</h2>
        <p><b>Nome: </b>{{$modalidade->nome}}</p>
        <p class=""><b>Descrição: </b>{{$modalidade->descricao}}</p>
        <p><b>Tipo: </b>{{$modalidade->tipoFormatado}}</p>
        <p><b>Coletivo: </b>{{$modalidade->coletivoFormatado}}</p>
        @if($modalidade->tipo === "pacote")
        <p><b>Período: </b>{{$modalidade->periodoFormatado}}</p>
        <p><b>Frequência: </b>{{$modalidade->frequenciaFormatada}}</p>
        <p>
            <b>Dias: </b> {{$agendamento->diasFormatados}}            
        </p>
        @endif
        <p><b>Valor (cão/hora): </b>{{$agendamento->precoPorCaoPorHoraFormatado}}</p>
    </section>
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
        <h2>Seus cachorros</h2>
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
        <h2>Passeios agendados</h2>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário de início</th>
                        <th>Horário de término</th>
                        <th>Porte</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($passeios as $passeio)
                    <?php
                    $statusClass = "";
                    switch ($passeio->status) {
                        case $statusPasseio["PENDENTE"]:
                            break;
                        case $statusPasseio["EM_ANDAMENTO"]:
                        case $statusPasseio["EM_ANALISE"]:
                            $statusClass = "_warning-color";
                            break;
                        case $statusPasseio["CANCELADO"]:
                            $statusClass = "_error-color";
                            break;
                        case $statusPasseio["FEITO"]:
                            $statusClass = "_success-color";
                            break;
                    }
                    ?>
                    <tr class="{{$statusClass}}">
                        <td>{{$passeio->dataFormatada}}</td>
                        <td>{{$passeio->inicioFormatado}}</td>
                        <td>{{$passeio->fimFormatado}}</td>
                        <td>{{$passeio->porteFormatado}}</td>
                        <td>{{$passeio->getValor($customer->idCliente, true)}}</td>
                        <td>{{$passeio->statusFormatado}}</td>
                        <td>
                            <div class="button-group">
                                <a class="btn btn-default" href="{{route("cliente.passeio.detalhes.get", ["id" => $passeio->idPasseio])}}">
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
    <div class="button-group">
        @if($agendamento->status !== $statusAgendamento["CANCELADO"])
        <a class="btn btn-danger" data-toggle="modal" data-target="#cancelamento-modal">
            <i class="glyphicon glyphicon-remove"></i>
            Cancelar
        </a>
        @endif
        @if($agendamento->status === $statusAgendamento["CLIENTE"])
        <button class="btn btn-success" data-action="aceitar-agendamento">
            <i class="glyphicon glyphicon-ok"></i>
            Aceitar
        </button>
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
                    <p>Caso tenha certeza do cancelamento do agendamento, por favor, informe-nos o motivo para tal:</p>
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

        $modal.find("[data-role='confirm-cancel-button']").click(function (ev) {
            ev.preventDefault();
            ev.stopPropagation();

            var motivo = $modal.find("textarea[name='motivo']").val();

            if (!motivo.trim()) {
                showAlert("Por favor, informe o motivo do cancelamento.");
                return;
            }
            $(this).defaultAjaxCall(
                    "{!! route('cliente.agendamento.cancelar.post', ['id' => $agendamento->idAgendamento]) !!}",
                    "POST",
                    "{!! route('cliente.agendamento.detalhes.get', ['id' => $agendamento->idAgendamento]) !!}",
                    {
                        "motivo": motivo
                    });
        });

        $modal.on("modal.bs.hidden", function () {
            $modal.find("textarea").val("");
        });

        $("[data-action='aceitar-agendamento']").on("click", function () {
            $(this).defaultAjaxCall(
                    "{!! route('cliente.agendamento.aceitar.post', ['id' => $agendamento->idAgendamento]) !!}",
                    "POST",
                    "{!! route('cliente.agendamento.detalhes.get', ['id' => $agendamento->idAgendamento]) !!}"
                    );
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

            var customerLatLng = new google.maps.LatLng(parseFloat("{!!$customer->lat!!}"), parseFloat("{!!$customer->lng!!}"));
            map.markers.push(new google.maps.Marker({
                position: customerLatLng,
                map: map,
                icon: "{!!asset('img/markers/user.png')!!}"
            }));
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