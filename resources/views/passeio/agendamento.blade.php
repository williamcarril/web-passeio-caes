<?php
$events = [];
foreach ($passeios as $passeio) {
    if (isset($events[$passeio->data])) {
        $events[$passeio->data]["number"] ++;
    } else {
        $events[$passeio->data] = [
            "number" => 1
        ];
    }
}
?>
@extends("layouts.default")

@section("title") Agendamento de passeios | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Agendamento de passeios</h1>
    @include("includes.calendar", ["id" => "calendario", "events" => $events])
    <div id="agendamento-wrapper" class="hidden">
        <h2>Horários do Dia - <span data-role="date"></span></h2>
        <div class="timetable-wrapper">
            <div id="timetable" class="timetable"></div>
        </div>
        <hr/>
        <p>
            * - Os locais exibidos na lista de horários do dia estão discriminados de acordo com o porte de seus passeios. Exemplo: Chico Mendes (Grande) diz respeito aos passeios de cães de grande porte que serão realizados no Chico Mendes.
        </p>
        <p>
            ** - Para selecionar os horários de início e fim, insira manualmente ou clique em alguma das horas na lista de horários do dia.
        </p>
        <p>
            *** - Para solicitar inclusão em algum dos passeios coletivos, clique um dos que estão disponíveis na lista de horários do dia.
        </p>
        <hr/>
        <form id="agendamento-form" class="form-horizontal" action="{!! route('passeio.agendamento.post') !!}" method="POST">
            <input type="hidden" name="idPasseioColetivo" value=""/>
            <fieldset data-name="dataEHorarios">
                <legend class="_cursor-pointer" data-toggle="collapse" data-target="#data-horario-collapsable">
                    Data e Horários
                    <i class="indicator glyphicon glyphicon-chevron-down"></i>
                </legend>
                <div id="data-horario-collapsable" class="collapse in">
                    <div class="form-group">
                        <input id='agendamento-data' value="" required name="data" type="hidden" class='form-control'>
                        <label class="control-label col-sm-2" for='agendamento-data'>Data</label>
                        <div class='col-sm-2'>
                            <p class='form-control-static' data-role='date'></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for='agendamento-inicio'>Horário inicial</label>
                        <div class='col-sm-2'>
                            <input id='agendamento-inicio' required name="inicio" type="time" class='form-control timepicker'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for='agendamento-fim'>Horário final</label>
                        <div class='col-sm-2'>
                            <input id='agendamento-fim' required name="fim" type="time" class='form-control timepicker'>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset data-role="modalidade">
                <legend  class="_cursor-pointer" data-toggle="collapse" data-target="#modalidade-collapsable">
                    Modalidade
                    <i class="indicator glyphicon glyphicon-chevron-down"></i>
                </legend>
                <div id="modalidade-collapsable" class="collapse in">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for='agendamento-modalidade'>Nome</label>
                        <div class='col-sm-3'>
                            <select required class="form-control" id="agendamento-modalidade" name="modalidade">
                                <option value="" selected>Selecione uma modalidade</option>
                                @foreach($modalidades as $modalidade)
                                <option value="{{$modalidade->idModalidade}}">{{$modalidade->nome}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hidden" data-role="modalidade-information">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Descriçao</label>
                            <div class="col-sm-8">
                                <p class='form-control-static _text-justify' data-name="descricao">
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Tipo</label>
                            <div class="col-sm-3">
                                <p class='form-control-static' data-name="tipo">
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Coletivo</label>
                            <div class="col-sm-3">
                                <p class='form-control-static' data-name="coletivo" data-value="">
                                </p>
                            </div>
                        </div>
                        <div class="hidden" data-role="package-only-fields">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Período</label>
                                <div class="col-sm-3">
                                    <p class='form-control-static' data-name="periodo">
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Frequência</label>
                                <div class="col-sm-3">
                                    <p class='form-control-static' data-name="frequencia" data-value="">
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Dias</label>
                                <div class="col-sm-6 _text-center">
                                    @foreach($dias as $dia)
                                    <label class="control-label col-sm-3 _text-center" for="agendamento-dia-{{$dia->idDia}}">
                                        {{$dia->nome}}
                                        <input name='modalidadeDias[]' class="form-control" id="agendamento-dia-{{$dia->idDia}}" type="checkbox" value="{{$dia->idDia}}" data-role="dia">
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Valor <br/>(cachorro / hora)</label>
                            <div class="col-sm-3">
                                <p class='form-control-static' data-name="precoPorCaoPorHora" data-value="">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <hr/>
            <p>
                **** - Para agendamentos de pacotes de passeio, a data do primeiro passeio agendado é o próximo dia da semana dos selecionados a partir da data escolhida no calendário.
            </p>
            <hr/>
            <fieldset data-role="local">
                <legend  class="_cursor-pointer" data-toggle="collapse" data-target="#local-collapsable">
                    Local de passeio
                    <i class="indicator glyphicon glyphicon-chevron-down"></i>
                </legend>
                <div id="local-collapsable" class="collapse in">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for='agendamento-local'>Nome</label>
                        <div class='col-sm-3'>
                            <select required class="form-control" id="agendamento-local" name="local">
                                @if(!empty($localPreSelecionado))
                                <option value="{{$localPreSelecionado->idLocal}}">{{$localPreSelecionado->nome}}</option>
                                @else
                                    <option value="" selected>Selecione um local de passeio</option>
                                    @foreach($locais as $local)
                                    <option value="{{$local->idLocal}}">{{$local->nome}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="hidden" data-role="local-information">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Imagem</label>
                            <div class="col-sm-3">
                                <img src="" data-name="imagem" alt=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2"></label>
                            <div class="col-sm-3">
                                <p class="form-control-static">
                                    <a class="btn btn-default" data-name="link" href="#" target="_blank">
                                        <i class="glyphicon glyphicon-search"></i>
                                        Detalhes
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset data-role="caes" style="display:table-cell; width: 100%">
                <legend  class="_cursor-pointer" data-toggle="collapse" data-target="#cao-collapsable">
                    Cachorros
                    <i class="indicator glyphicon glyphicon-chevron-down"></i>
                </legend>
                <div id="cao-collapsable" class="collapse in">
                    <p class="hidden" data-role="porteDoPasseioWrapper"><b>Porte do passeio: </b><span data-role="porteDoPasseio"></span></p>
                    <p>Selecione quais de seus cachorros participarão do(s) passeio(s) agendado(s):</p>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nome</th>
                                    <th>Porte</th>
                                    <th>Participação</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($caes as $cao)
                                <tr data-id="{{$cao->idCao}}" data-role="cao" class="_error-color">
                                    <td class="image">
                                        <img width="100px" height="100px" src='{{$cao->thumbnail}}' />
                                    </td>
                                    <td data-name="nome">
                                        {{$cao->nome}}
                                    </td>
                                    <td  data-name="porte" data-value="{{$cao->porte}}">
                                        {{$cao->porteFormatado}}
                                    </td>
                                    <td data-name="participacao">
                                        <input class="hidden" type="checkbox" name="caes[]" value="{{$cao->idCao}}" data-role="value"/>
                                        <span data-role="label">
                                            Não participará
                                        </span>
                                    </td>
                                    <td>
                                        <div class="button-group">
                                            <button class="btn btn-success" type="button" data-action="incluir-cao">
                                                <i class="glyphicon glyphicon-ok"></i>
                                                Incluir
                                            </button>
                                            <button class="btn btn-danger hidden" type="button" data-action="remover-cao">
                                                <i class="glyphicon glyphicon-remove"></i>
                                                Remover
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
            <hr/>
            <p><b>Duração do passeio:</b> <span data-role="duracao">Não definido</span></p>
            <p><b>Preço por passeio:</b> <span data-role="precoPorPasseio">Não definido</span></p>
            <p><b>Quantidade de passeios:</b> <span data-role="quantidadePasseio">Não definido</span></p>
            <p><b>Preço total:</b> <span data-role="precoTotal">Não definido</span></p>
            <hr/>
            <div class="button-group">
                <button class="btn btn-warning hidden" type="button" data-action="cancelar-passeio-coletivo">
                    <i class="glyphicon glyphicon-remove"></i>
                    Cancelar inclusão
                </button>
                <button class="btn btn-success" type="submit">
                    <i class="glyphicon glyphicon-ok"></i>
                    Solicitar
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@section("scripts")
<script type="text/javascript">
    (function () {
        var $calendario = $("#calendario");
        var $wrapper = $("#agendamento-wrapper");
        var $form = $wrapper.find("#agendamento-form");
        var $timetable = $wrapper.find("#timetable");

        var $dataEHorarios = $wrapper.find("fieldset[data-name='dataEHorarios']");

        var $modalidade = $form.find("[data-role='modalidade']");
        var $modalidadeInformation = $modalidade.find("[data-role='modalidade-information']");
        var $packageOnlyFields = $modalidadeInformation.find("[data-role='package-only-fields']");
        var $modalidadeDias = $packageOnlyFields.find("[data-role='dia']");
        var $modalidadeSelect = $modalidade.find("select[name='modalidade']");

        var $local = $form.find("[data-role='local']");
        var $localInformation = $local.find("[data-role='local-information']");
        var $localSelect = $local.find("select[name='local']");

        var $caes = $form.find("[data-role='caes']");
        var $porteDoPasseioWrapper = $caes.find("[data-role='porteDoPasseioWrapper']");
        var $porteDoPasseio = $porteDoPasseioWrapper.find("[data-role='porteDoPasseio']");


        var $precoPorPasseio = $form.find("[data-role='precoPorPasseio']");
        var $precoTotal = $form.find("[data-role='precoTotal']");
        var $quantidadePasseio = $form.find("[data-role='quantidadePasseio']");
        var $duracao = $form.find("[data-role='duracao']");

        var $btnCancelarPasseioColetivo = $form.find("[data-action='cancelar-passeio-coletivo']");

        var $startingTime = $form.find("input[name='inicio']");
        var $endingTime = $form.find("input[name='fim']");

        var url = "{!! route('passeio.porData.json.get', ['dia' => '!dia', 'mes' => '!mes', 'ano' => '!ano']) !!}";
        var urlSemDia = "{!! route('passeio.porData.json.get', ['mes' => '!mes', 'ano' => '!ano']) !!}";
        var dayClickAjax = null;
        
        @if(isset($localPreSelecionado))
            carregarDetalhesDoLocal("{!!$localPreSelecionado->idLocal!!}", false);
        @endif

        $calendario.responsiveCalendar({
            "translateMonths": globals.months,
            "onMonthChange": function () {
                var month = this.currentMonth + 1;
                var year = this.currentYear;
                $.ajax({
                    "url": urlSemDia.replace("!mes", month).replace("!ano", year),
                    "type": "GET",
                    "data": {
                        "coletivo": true
                    },
                    "beforeSend": function () {
                        $timetable.fadeOut(400);
                    },
                    "success": function (response) {
                        if (!response.status) {
                            return;
                        }
                        var events = {};
                        var passeio;
                        for (var i in response.data.passeios) {
                            passeio = response.data.passeios[i];
                            if (events[passeio.data]) {
                                events[passeio.data].number++;
                            } else {
                                events[passeio.data] = {
                                    "number": 1
                                };
                            }
                        }
                        $calendario.responsiveCalendar("edit", events);
                    },
                    "error": function (request) {
                        if (request.statusText === 'abort') {
                            return;
                        }
                        showAlert("{!!trans('alert.error.request')!!}", "error");
                    },
                    "complete": function () {
                    }
                });
            },
            "onDayClick": function (event) {
                var $this = $(this);
                var day = $this.attr("data-day");
                var month = $this.attr("data-month");
                var year = $this.attr("data-year");

                if (dayClickAjax !== null) {
                    dayClickAjax.abort();
                }

                dayClickAjax = $.ajax({
                    "url": url.replace("!dia", day).replace("!mes", month).replace("!ano", year),
                    "type": "GET",
                    "data": {
                        "coletivo": true,
                        "discriminarPorte": true
                    },
                    "beforeSend": function () {
                        restaurarEstadoBaseDaTela();
                        var clickedDate = simpleDateFormatter(day, month, year, "Y-m-d");
                        $wrapper.find("[data-role='date']").text(simpleDateFormatter(day, month, year));
                        $form.find("input[name='data']").val(clickedDate);
                        $form.find("input[name='idPasseioColetivo']").val("");

                        //Verificar se a data é anterior à atual e não exibir formulário de agendamento caso positivo.
                        if (clickedDate <= simpleDateFormatter(null, null, null, "Y-m-d")) {
                            $form.addClass("hidden");
                        } else {
                            $form.removeClass("hidden");
                        }

                        $wrapper.removeClass("hidden");
                        $timetable.fadeOut(400);
                    },
                    "success": function (response) {
                        if (!response.status) {
                            return;
                        }
                        var timetable = new Timetable();
                        timetable.setScope(parseInt("{!!$businessStartingTime!!}"), parseInt("{!!$businessEndingTime!!}"));
                        var renderer = new Timetable.Renderer(timetable);

                        timetable.addLocations(response.data.locais);
                        var passeio, inicio, fim;
                        for (var i in response.data.passeios) {
                            passeio = response.data.passeios[i];
                            inicio = new Date(passeio.data + " " + passeio.inicio);
                            fim = new Date(passeio.data + " " + passeio.fim);

                            timetable.addEvent(passeio.tipo, passeio.local, inicio, fim, {
                                "data": {
                                    "id": passeio.idPasseio,
                                    "inicio": passeio.inicio,
                                    "fim": passeio.fim,
                                    "idLocal": passeio.idLocal,
                                    "porte": passeio.porte
                                }
                            });
                        }
                        renderer.draw('#timetable'); // any css selector
                        $timetable.fadeIn(400);
                        $wrapper.scrollView();
                    },
                    "error": function (request) {
                        if (request.statusText === 'abort') {
                            return;
                        }
                        showAlert("{!!trans('alert.error.request')!!}", "error");
                    },
                    "complete": function () {
                        dayClickAjax = null;
                    }
                });
            }
        });

        $startingTime.on("change", function () {
            if(Date.parse("2000-01-01 " + $startingTime.val()) < Date.parse("2000-01-01 {!! $businessStartingTime !!}")) {
                $startingTime.val("{!! $businessStartingTime !!}");
            }
        });
        
        $startingTime.on("blur", function() {
            if(validate.empty($startingTime.val())) {
                setInputStatus($startingTime, "error");
                return;
            }
            if(Date.parse("2000-01-01 " + $startingTime.val()) >= Date.parse("2000-01-01 " + $endingTime.val())) {
                setInputStatus($startingTime, "error");
                return;
            }
            setInputStatus($startingTime, "success");
            limparEstadoDaTimetable();
            obterEDefinirTotais();
        });
        
        $endingTime.on("blur", function() {
            if(validate.empty($endingTime.val())) {
                setInputStatus($endingTime, "error");
                return;
            }
            if(Date.parse("2000-01-01 " + $endingTime.val()) <= Date.parse("2000-01-01 " + $startingTime.val())) {
                setInputStatus($endingTime, "error");
                return;
            }
            setInputStatus($endingTime, "success");
            limparEstadoDaTimetable();
            obterEDefinirTotais();
        });
        
        $endingTime.on("change", function () {            
            if(Date.parse("2000-01-01 " + $endingTime.val() > Date.parse("2000-01-01 {!! $businessEndingTime !!}"))) {
                $endingTime.val("{!! $businessEndingTime !!}");
            }
        });

        $timetable.on("click", ".time-label", function () {
            var $this = $(this);
            var clickedDate = $form.find("input[name='data']").val();

            //Verificar se a data é anterior à atual e não exibir formulário de agendamento caso positivo.
            if (clickedDate <= simpleDateFormatter(null, null, null, "Y-m-d")) {
                return;
            }
            
            if ($this.hasClass("-starting-time")) {
                $this.removeClass("-starting-time");
                definirHorarios("starting", null);
                return;
            }
            if ($this.hasClass("-ending-time")) {
                $this.removeClass("-ending-time");
                definirHorarios("ending", null);
                return;
            }
            var $startingLabel = $timetable.find(".-starting-time");
            var $endingLabel = $timetable.find(".-ending-time");

            if ($startingLabel.length === 0 && $endingLabel.length === 0) {
                $this.addClass("-starting-time");
                definirHorarios("starting", $this.text());
                return;
            }

            if ($startingLabel.length === 0) {
                if ($this.text() < $endingLabel.text()) {
                    $this.addClass("-starting-time");
                    definirHorarios("starting", $this.text());
                } else {
                    $endingLabel.removeClass("-ending-time").addClass("-starting-time");
                    definirHorarios("starting", $endingLabel.text(), false);
                    $this.addClass("-ending-time");
                    definirHorarios("ending", $this.text());
                }
                return;
            }

            if ($endingLabel.length === 0) {
                if ($this.text() > $startingLabel.text()) {
                    $this.addClass("-ending-time");
                    definirHorarios("ending", $this.text());
                } else {
                    $startingLabel.removeClass("-starting-time").addClass("-ending-time");
                    definirHorarios("ending", $startingLabel.text(), false);
                    $this.addClass("-starting-time");
                    definirHorarios("starting", $this.text());
                }
                return;
            }

            if ($this.text() < $startingLabel.text()) {
                $startingLabel.removeClass("-starting-time");
                $this.addClass("-starting-time");
                definirHorarios("starting", $this.text());
            } else {
                $endingLabel.removeClass("-ending-time");
                $this.addClass("-ending-time");
                definirHorarios("ending", $this.text());
            }
        });

        $timetable.on("click", ".time-entry", function () {
            var $this = $(this);
            var clickedDate = $form.find("input[name='data']").val();
            //Verificar se a data é anterior à atual e não exibir formulário de agendamento caso positivo.
            if (clickedDate <= simpleDateFormatter(null, null, null, "Y-m-d")) {
                return;
            }
            askConfirmation("Inclusão em passeio coletivo", "Deseja iniciar uma solicitação de agendamento para o passeio coletivo selecionado?", function () {
                var idPasseio = $this.attr("data-id");
                var inicio = $this.attr("data-inicio");
                var fim = $this.attr("data-fim");
                var idLocal = $this.attr("data-idLocal");
                var porte = $this.attr("data-porte");
                prepararInclusaoParaPasseioColetivo(idPasseio, inicio, fim, idLocal, porte);
            });
        });

        $modalidadeSelect.change(function (ev) {
            var $this = $(this);
            var idModalidade = $this.val();
            carregarDetalhesDaModalidade(idModalidade, true);
        });

        $localSelect.change(function (ev) {
            var $this = $(this);
            var idLocal = $this.val();
            carregarDetalhesDoLocal(idLocal, true);
        });

        $modalidadeDias.on("change", function (ev) {
            var $this = $(this);
            var maxDias = $packageOnlyFields.find("[data-name='frequencia']").attr("data-value");
            var $diasChecados = $modalidadeDias.filter(":checked");
            if ($diasChecados.length > maxDias) {
                ev.preventDefault();
                ev.stopPropagation();
                $this.prop("checked", false);
            }
        });

        $caes.on("click", "[data-action='incluir-cao']", function (ev) {
            var $this = $(this);
            var $cao = $this.parents("[data-role='cao']");
            alterarParticipacao($cao, true);
        });
        $caes.on("click", "[data-action='remover-cao']", function (ev) {
            var $this = $(this);
            var $cao = $this.parents("[data-role='cao']");
            alterarParticipacao($cao, false);
        });

        $form.find("select[name='local']").validate("empty", null, "blur");
        $form.find("select[name='modalidade']").validate("empty", null, "blur");

        $btnCancelarPasseioColetivo.on("click", function (ev) {
            restaurarEstadoBaseDaTela();
            $wrapper.scrollView();
        });

        $form.defaultAjaxSubmit("{!! route('cliente.agendamento.get') !!}", function () {
            var $caesQueParticiparao = $caes.find("[data-role='cao']").filter(function () {
                return $(this).find("input[name='caes[]']:checked").length > 0;
            });

            if ($caesQueParticiparao.length === 0) {
                showAlert("Por favor, selecione um de seus cães para participar do passeio.");
                $caes.scrollView();
                return false;
            }

            var pacote = $modalidade.attr("data-package") == "1" ? true : false;

            var maxDias = $packageOnlyFields.find("[data-name='frequencia']").attr("data-value");
            var $diasChecados = $modalidadeDias.filter(":checked");
            if ($diasChecados.length < maxDias) {
                showAlert("Por favor, selecione " + maxDias + " dias da semana para seu pacote de passeios.");
                $modalidade.scrollView();
                return false;
            }

            return true;
        });

        function definirHorarios(type, time, definirTotais) {
            time = time || "";
            definirTotais = definirTotais || true;
            switch (type) {
                case "starting":
                    $startingTime.val(time);
                    $startingTime.validate("empty", null);
                    break;
                case "ending":
                    $endingTime.val(time);
                    $endingTime.validate("empty", null);
                    break;
            }
            if (definirTotais) {
                obterEDefinirTotais();
            }
        }

        function alterarParticipacao($cao, participacao) {
            var $participacao = $cao.find("[data-name='participacao']");
            var $btnIncluir = $cao.find("[data-action='incluir-cao']");
            var $btnRemover = $cao.find("[data-action='remover-cao']");
            var $value = $participacao.find("[data-role='value']");
            var $label = $participacao.find("[data-role='label']");

            if (participacao) {
                var coletivo = $modalidade.find("[data-name='coletivo']").attr("data-value");
                if (coletivo == 1) {
                    //Porte do passeio coletivo em questão
                    var porteDoPasseio = $porteDoPasseio.attr("data-value");

                    var $caesQueParticiparao = $caes.find("[data-role='cao']").filter(function () {
                        return $(this).find("input[name='caes[]']:checked").length > 0;
                    });
                    var porte = $cao.find("[data-name='porte']").attr("data-value");

                    if (porteDoPasseio && porte !== porteDoPasseio) {
                        showAlert("Somente cães de porte '" + porteDoPasseio + "' podem fazer parte deste passeio coletivo.", "warning");
                        return;
                    }

                    for (var i = 0; i < $caesQueParticiparao.length; i++) {
                        var porteDoCaoQueParticipara = $caesQueParticiparao.eq(i).find("[data-name='porte']").attr("data-value");
                        if (porte !== porteDoCaoQueParticipara) {
                            showAlert("Somente cães de porte iguais podem fazer parte de um mesmo passeio coletivo.", "warning");
                            return;
                        }
                    }
                }

                $value.prop("checked", true);
                $label.text("Participará");
                $btnIncluir.addClass("hidden");
                $btnRemover.removeClass("hidden");
                $cao.addClass("_success-color").removeClass("_error-color");
            } else {
                $value.prop("checked", false);
                $label.text("Não participará");
                $btnRemover.addClass("hidden");
                $btnIncluir.removeClass("hidden");
                $cao.addClass("_error-color").removeClass("_success-color");
            }
            obterEDefinirTotais();
        }

        function prepararInclusaoParaPasseioColetivo(idPasseio, inicio, fim, idLocal, porte) {
            resetarCampos();
            
            $form.find("input[name='idPasseioColetivo']").val(idPasseio);

            $startingTime.val(inicio);
            $startingTime.prop("disabled", true);
            $endingTime.val(fim);
            $endingTime.prop("disabled", true);
            var $localSelect = $local.find("select[name='local']");
            $localSelect.find("option[value='" + idLocal + "']").prop("selected", true);
            carregarDetalhesDoLocal(idLocal, false);
            $localSelect.prop("disabled", true);

            var $modalidadeSelect = $modalidade.find("select[name='modalidade']");
            $modalidadeSelect.find("option[value='{!! $idModalidadeBaseColetiva !!}']").prop("selected", true);
            carregarDetalhesDaModalidade("{!!$idModalidadeBaseColetiva!!}", false);
            $modalidadeSelect.prop("disabled", true);

            $porteDoPasseio.attr("data-value", porte).text(porte);
            $porteDoPasseioWrapper.removeClass("hidden");
            $btnCancelarPasseioColetivo.removeClass("hidden");

            limparEstadoDaTimetable();

            $caes.scrollView();
        }

        function carregarDetalhesDaModalidade(idModalidade, scrollView) {
            scrollView = scrollView || false;
            $modalidadeDias.prop("checked", false);
            if (!idModalidade) {
                $modalidadeInformation.addClass("hidden");
                $packageOnlyFields.addClass("hidden");
                return;
            }
            $.ajax({
                "url": "{!!route('modalidade.json.get', ['id' => '!id'])!!}".replace("!id", idModalidade),
                "type": "GET",
                "beforeSend": function () {
                    $modalidadeSelect.addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.message);
                        return;
                    }
                    var modalidade = response.data;

                    $modalidadeInformation.find("[data-name='descricao']").text(modalidade.descricao);
                    $modalidadeInformation.find("[data-name='tipo']").text(modalidade.tipo);

                    var $coletivo = $modalidadeInformation.find("[data-name='coletivo']");
                    $coletivo.text(modalidade.coletivo ? "Sim" : "Não");
                    $coletivo.attr("data-value", modalidade.coletivo ? "1" : "0");

                    var $preco = $modalidadeInformation.find("[data-name='precoPorCaoPorHora']");
                    $preco.text(formatMoney(modalidade.precoPorCaoPorHora));
                    $preco.attr("data-value", modalidade.precoPorCaoPorHora);

                    $modalidadeInformation.find("[data-name='periodo']").text(modalidade.periodo);

                    var $frequencia = $modalidadeInformation.find("[data-name='frequencia']");
                    $frequencia.text(modalidade.frequencia);
                    $frequencia.attr("data-value", modalidade.frequenciaNumericaPorSemana);

                    var $periodo = $modalidadeInformation.find("[data-name='periodo']");
                    $periodo.text(modalidade.periodo);
                    $periodo.attr("data-value", modalidade.periodoNumericoPorMes);

                    if (modalidade.pacote) {
                        $modalidade.attr("data-package", 1);
                        $packageOnlyFields.removeClass("hidden");
                    } else {
                        $modalidade.attr("data-package", 0);
                        $packageOnlyFields.addClass("hidden");
                    }

                    $modalidadeInformation.removeClass("hidden");
                    if (scrollView) {
                        $modalidade.scrollView();
                    }

                    resetarParticipacaoDosCaes();

                    $caes.find(".collapse").collapse("show");

                    $precoPorPasseio.text("Não definido");
                    $precoTotal.text("Não definido");
                },
                "error": function (request) {
                    if (request.statusText === 'abort') {
                        return;
                    }
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $modalidadeSelect.removeClass("loading");
                }
            });
        }

        function carregarDetalhesDoLocal(idLocal, scrollView) {
            scrollView = scrollView || false;
            if (!idLocal) {
                $localInformation.addClass("hidden");
                return;
            }
            $.ajax({
                "url": "{!!route('local.json.get', ['id' => '!id'])!!}".replace("!id", idLocal),
                "type": "GET",
                "data": {
                    "fields": "link,thumbnail,nome"
                },
                "beforeSend": function () {
                    $localSelect.addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.message);
                        return;
                    }
                    var local = response.data;

                    $localInformation.find("[data-name='imagem']").attr("src", local.thumbnail).attr("alt", local.nome);
                    $localInformation.find("[data-name='link']").attr("href", local.link);

                    $localInformation.removeClass("hidden");
                    if (scrollView) {
                        $local.scrollView();
                    }
                },
                "error": function (request) {
                    if (request.statusText === 'abort') {
                        return;
                    }
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $localSelect.removeClass("loading");
                }
            });
        }

        function restaurarEstadoBaseDaTela() {
            $btnCancelarPasseioColetivo.addClass("hidden");
            
            resetarCampos();
            resetarParticipacaoDosCaes();
            limparEstadoDaTimetable();
            limparTotais();

            $dataEHorarios.find(".collapse").collapse("show");
        }

        function resetarCampos() {
            $form.find("input[name='idPasseioColetivo']").val("");
            
            $startingTime.val("");
            $startingTime.prop("disabled", false);
            
            $endingTime.val("");
            $endingTime.prop("disabled", false);
            
            var $localSelect = $local.find("select[name='local']");
            $localSelect.find("option[value='']").prop("selected", true);
            $localSelect.trigger("change");
            $localSelect.prop("disabled", false);
            
            var $modalidadeSelect = $modalidade.find("select[name='modalidade']");
            $modalidadeSelect.find("option[value='']").prop("selected", true);
            $modalidadeSelect.trigger("change");
            $modalidadeSelect.prop("disabled", false);

            $porteDoPasseio.attr("data-value", "").text("");
            $porteDoPasseioWrapper.addClass("hidden");
            
            $form.find("input,select").filter(".-error,.-success").removeClass("-error").removeClass("-success");
        }

        function resetarParticipacaoDosCaes() {
            var $caesQueParticiparao = $caes.find("[data-role='cao']").filter(function () {
                return $(this).find("input[name='caes[]']:checked").length > 0;
            });
            for (var i = 0; i < $caesQueParticiparao.length; i++) {
                var $cao = $caesQueParticiparao.eq(i);
                alterarParticipacao($cao, false);
            }
        }

        function limparTotais() {
            $duracao.text("Não definido");
            $quantidadePasseio.text("Não definido");
            $precoPorPasseio.text("Não definido");
            $precoTotal.text("Não definido");
        }

        function limparEstadoDaTimetable() {
            $timetable.find(".time-label.-starting-time,.time-label.-ending-time").removeClass("-starting-time").removeClass("-ending-time");
        }

        function obterEDefinirDuracao() {
            var starting = $startingTime.val();
            var ending = $endingTime.val();

            if (!starting || !ending) {
                $duracao.text("Não definido");
                return null;
            }
            var interval = diffTime(ending, starting);

            $duracao.text(formatTime(interval));
            return interval;
        }

        function obterEDefinirQuantidadeDePasseios() {
            var idModalidade = $modalidade.find("select[name='modalidade']").val();

            if (!idModalidade) {
                $quantidadePasseio.text("Não definido");
                return null;
            }
            var pacote = $modalidade.attr("data-package") == "1" ? true : false;
            if (!pacote) {
                $quantidadePasseio.text(1);
                return 1;
            } else {
                var frequencia = $modalidade.find("[data-name='frequencia']").attr("data-value");
                var periodo = $modalidade.find("[data-name='periodo']").attr("data-value");
                var quantidadePasseios = frequencia * 4 * periodo;
                $quantidadePasseio.text(quantidadePasseios);
                return quantidadePasseios;
            }
        }

        function obterEDefinirTotais() {
            var interval = obterEDefinirDuracao();
            var quantidadePasseios = obterEDefinirQuantidadeDePasseios();

            if (interval === null) {
                $precoPorPasseio.text("Não definido");
                $precoTotal.text("Não definido");
                return null;
            }

            if (quantidadePasseios === null) {
                $precoPorPasseio.text("Não definido");
                $precoTotal.text("Não definido");
                return null;
            }

            var $caesQueParticiparao = $caes.find("[data-role='cao']").filter(function () {
                return $(this).find("input[name='caes[]']:checked").length > 0;
            });
            if ($caesQueParticiparao.length <= 0) {
                $precoPorPasseio.text("Não definido");
                $precoTotal.text("Não definido");
                return null;
            }
            var precoPorCaoPorHora = $modalidade.find("[data-name='precoPorCaoPorHora']").attr("data-value");
            if (!precoPorCaoPorHora) {
                $precoPorPasseio.text("Não definido");
                $precoTotal.text("Não definido");
                return null;
            }
            precoPorCaoPorHora = parseFloat(precoPorCaoPorHora);

            var precoPorPasseio = $caesQueParticiparao.length * interval * precoPorCaoPorHora;
            $precoPorPasseio.text(formatMoney(precoPorPasseio));

            if (quantidadePasseios === 1) {
                $precoTotal.text($precoPorPasseio.text());
            } else {
                var precoTotal = precoPorPasseio * quantidadePasseios;
                $precoTotal.text(formatMoney(precoTotal));
            }
            return {
                "porPasseio": precoPorPasseio,
                "geral": precoTotal
            };
        }
    })();
</script>
@endsection