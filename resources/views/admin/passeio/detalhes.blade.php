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
    <p><b>Preço (total):</b> {{$passeio->getValor(null, true)}}</p>
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
        <h2>Passeador</h2>
        <div id="passeador-wrapper" class="row">
            @if(is_null($passeador))
            <div class="col-lg-6">
                <p class="_error-color">Não alocado</p>
                @if($passeio->status === $statusPasseio["PENDENTE"])
                <a class="btn btn-default" href="#passeador-modal" data-toggle="modal">Alocar passeador</a>
                @endif
            </div>
            @else
            <div data-role="passeador">
                <div class="col-lg-2 ">
                    <img data-name="thumbnail" alt="{{$passeador->nome}}" src="{{ $passeador->thumbnail }}"/>
                </div>
                <div class="col-lg-6">
                    <p><b>Nome: </b> <span data-name="nome">{{$passeador->nome}}</span></p>
                    <p><b>Telefone: </b> <span data-name="telefone">{{$passeador->telefoneFormatado}}</span></p>
                    <p><b>E-mail: </b> <span data-name="email">{{$passeador->email}}</span></p>
                </div>
                
                @if($passeio->status === $statusPasseio["PENDENTE"])
                <div class="col-lg-6">
                    <a class="btn btn-default" href="#passeador-modal" data-toggle="modal">Realocar passeador</a>
                    <button class="btn btn-danger" data-action="remover-passeador">Remover passeador</button>
                </div>
                @endif
            </div>
            @endif
        </div>
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
        @endif
    </section>
    <section>
        <h2>Agendamentos para este passeio</h2>
        <div class="table-responsive">
            @include("admin.includes.agendamentos-tabela", ["agendamentos" => $agendamentos, "passeio" => $passeio])
        </div>
    </section>
    <section>
        <h2>Cancelamentos para este passeio</h2>
        @if($cancelamentos->count() > 0)
        <div class="table-responsive">
            @include("admin.includes.cancelamentos-tabela", ["cancelamentos" => $cancelamentos])
        </div>
        @else
        <p>Não há cancelamentos para este passeio.</p>
        @endif
    </section>
    <hr/>
    <div class="button-group pull-right">
        @if($passeio->status !== $statusPasseio["CANCELADO"] && $passeio->status !== $statusPasseio["FEITO"])
        <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelamento-modal">Cancelar passeio</a>
        @endif
        @if($passeio->status === $statusPasseio["PENDENTE"] && strtotime(date("Y-m-d")) > strtotime($passeio->data))
        <button class="btn btn-warning" data-action="marcar-feito">Marcar como feito</button>
        @endif
        @if($passeio->status === $statusPasseio["EM_ANALISE"] && $passeio->foiOriginado())
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
                    <button data-role="confirm-button" type="button" class="btn btn-success">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </footer>
            </section>
        </div>
    </div>
    <div id="passeador-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <section class="modal-content">
                <header class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h1 class="modal-title" data-role="title">Selecione o passeador</h1>
                </header>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Telefone</th>
                                    <th>E-mail</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($passeadoresAptos as $funcionario)
                                <tr data-id="{{$funcionario->idFuncionario}}" class="_cursor-pointer" data-role="passeador">
                                    <td><img src="{{$funcionario->thumbnail}}" alt="Foto"/></td>
                                    <td>{{$funcionario->nome}}</td>
                                    <td>{{$funcionario->cpfFormatado}}</td>
                                    <td>{{$funcionario->rg}}</td>
                                    <td>{{$funcionario->telefoneFormatado}}</td>
                                    <td>{{$funcionario->email}}</td>
                                    <td>
                                        <div class="button-group">
                                            <a href="{{route("admin.funcionario.passeador.alterar.get", ["id" => $funcionario->idFuncionario])}}" class="btn btn-default" target="_blank">
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
                </div>
                <footer class="modal-footer">
                    <button data-role="confirm-button" type="button" class="btn btn-success disabled">Confirmar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </footer>
            </section>
        </div>
    </div>
</section>
@endsection

@section("templates")
<div data-template="passeador" data-role="passeador">
    <div class="col-lg-2 ">
        <img data-name="thumbnail" alt="" src=""/>
    </div>
    <div class="col-lg-6">
        <p><b>Nome: </b> <span data-name="nome"></span></p>
        <p><b>Telefone: </b> <span data-name="telefone"></span></p>
        <p><b>E-mail: </b> <span data-name="email"></span></p>
    </div>
    <div class="col-lg-6">
        <a class="btn btn-default" href="#passeador-modal" data-toggle="modal">Realocar passeador</a>
        <button class="btn btn-danger" data-action="remover-passeador">Remover passeador</button>
    </div>
</div>

<div data-template="sem-passeador">
    <div class="col-lg-6">
        <p class="_error-color">Não alocado</p>
        <a class="btn btn-default" href="#passeador-modal" data-toggle="modal">Alocar passeador</a>
    </div>
</div>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $modalCancelamento = $("#cancelamento-modal");
        var $modalPasseador = $("#passeador-modal");
        var $passeadorWrapper = $("#passeador-wrapper");

        $("[data-action='marcar-feito']").on("click", function (ev) {
            askConfirmation("Conclusão de passeio", "Tem certeza que deseja marcar este passeio como feito?", function () {
                $(this).defaultAjaxCall(
                        "{!! route('admin.passeio.status.feito.post', ['id' => $passeio->idPasseio]) !!}",
                        "POST",
                        "{!! route('admin.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}"
                        );
            });
        });

        $passeadorWrapper.on("click", "[data-action='remover-passeador']", function () {
            $(this).defaultAjaxCall(
                    "{!!route('admin.passeio.alocar.passeador.post', ['id' => $passeio->idPasseio])!!}",
                    "POST", null, null, null, null, {
                        "success": {
                            "afterSuccess": function (response) {
                                var passeador = response.data;
                                var $passeadorTemplate = globals.templates.find("[data-template='sem-passeador']");

                                var $clone = $passeadorTemplate.clone();
                                $clone.removeAttr("data-template");

                                $passeadorWrapper.html($clone.html());
                                $modalPasseador.modal("hide");
                            }
                        }
                    });
        });

        $modalPasseador.on("click", "[data-role='passeador']", function () {
            var $this = $(this);
            var $selected = $modalPasseador.find("[data-role='passeador'][data-status='selected']");
            $selected.removeAttr("data-status").removeClass("bg-success");
            $this.addClass("bg-success").attr("data-status", "selected");
            var $confirmButton = $modalPasseador.find("[data-role='confirm-button']");
            $confirmButton.removeClass("disabled");
        });

        $modalPasseador.on("hidden.bs.modal", function () {
            var $selected = $modalPasseador.find("[data-role='passeador'][data-status='selected']");
            $selected.removeClass("bg-success").removeAttr("data-status");
            var $confirmButton = $modalPasseador.find("[data-role='confirm-button']");
            $confirmButton.addClass("disabled");
        });

        $modalPasseador.on("click", "[data-role='confirm-button']", function () {
            var $this = $(this);
            var $selected = $modalPasseador.find("[data-role='passeador'][data-status='selected']");
            var idPasseador = $selected.attr("data-id");
            $this.defaultAjaxCall(
                    "{!!route('admin.passeio.alocar.passeador.post', ['id' => $passeio->idPasseio])!!}",
                    "POST", null, {"idPasseador": parseInt(idPasseador)}, null, null, {
                "success": {
                    "afterSuccess": function (response) {
                        var passeador = response.data;
                        var $passeadorTemplate = globals.templates.find("[data-template='passeador']");

                        var $clone = $passeadorTemplate.clone();
                        $clone.removeAttr("data-template");
                        $clone.find("[data-name='nome']").text(passeador.nome);
                        $clone.find("[data-name='telefone']").text(passeador.telefone);
                        $clone.find("[data-name='email']").text(passeador.email);
                        $clone.find("[data-name='thumbnail']").attr("src", passeador.thumbnail).attr("alt", passeador.nome);

                        $passeadorWrapper.html($clone.html());
                        $modalPasseador.modal("hide");
                    }
                }
            });
        });

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
                    "{!! route('admin.passeio.cancelar.post', ['id' => $passeio->idPasseio]) !!}",
                    "POST",
                    "{!! route('admin.passeio.detalhes.get', ['id' => $passeio->idPasseio]) !!}",
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