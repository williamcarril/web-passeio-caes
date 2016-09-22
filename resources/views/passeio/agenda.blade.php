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
        <h2>Horários do Dia</h2>
        <div class="timetable-wrapper">
            <div id="timetable" class="timetable"></div>
        </div>
        <form id="agendamento-form" class="form-horizontal">
            <fieldset>
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
                            <select class="form-control" id="agendamento-modalidade" name="modalidade">
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
                                <p class='form-control-static' data-name="coletivo">
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
                                    <p class='form-control-static' data-name="frequencia">
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Valor <br/>(cachorro / hora)</label>
                            <div class="col-sm-3">
                                <p class='form-control-static' data-name="precoPorCaoPorHora">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <fieldset data-role="local">
                <legend  class="_cursor-pointer" data-toggle="collapse" data-target="#local-collapsable">
                    Local de passeio
                    <i class="indicator glyphicon glyphicon-chevron-down"></i>
                </legend>
                <div id="local-collapsable" class="collapse in">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for='agendamento-local'>Nome</label>
                        <div class='col-sm-3'>
                            <select class="form-control" id="agendamento-local" name="local">
                                <option value="" selected>Selecione um local de passeio</option>
                                @foreach($locais as $local)
                                <option value="{{$local->idLocal}}">{{$local->nome}}</option>
                                @endforeach
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
                            <label class="control-label col-sm-2">Link para detalhes</label>
                            <div class="col-sm-3">
                                <p class="form-control-static">
                                    <a data-name="link" href="#" target="_blank">
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <hr/>
            <p>
                * - Para selecionar os horários de início e fim, insira manualmente ou clique em alguma das horas na lista de horários do dia.
            </p>
            <p>
                ** - Para solicitar inclusão em algum dos passeios coletivos, clique um dos que estão disponíveis na lista de horários do dia..
            </p>
            <hr/>
            <a class="btn btn-success pull-right" href="#">
                Confirmar agendamento
            </a>
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

        var $modalidade = $form.find("[data-role='modalidade']");
        var $modalidadeInformation = $modalidade.find("[data-role='modalidade-information']");

        var $local = $form.find("[data-role='local']");
        var $localInformation = $local.find("[data-role='local-information']");

        var $startingTime = $form.find("input[name='inicio']");
        var $endingTime = $form.find("input[name='fim']");

        var url = "{!! route('passeio.porData.json.get', ['dia' => '!dia', 'mes' => '!mes', 'ano' => '!ano']) !!}";
        var urlSemDia = "{!! route('passeio.porData.json.get', ['mes' => '!mes', 'ano' => '!ano']) !!}";
        var dayClickAjax = null;

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
                        "coletivo": true
                    },
                    "beforeSend": function () {
                        $form.find("[data-role='date']").text(simpleDateFormatter(day, month, year));
                        $form.find("input[name='data']").val(simpleDateFormatter(day, month, year, "Y-m-d"));
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

                            timetable.addEvent(passeio.tipo, passeio.local, inicio, fim);
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

        $form.on("change", "input[name='inicio'],input[name='fim']", function () {
            $timetable.find(".time-label.-starting-time,.time-label.-ending-time").removeClass("-starting-time").removeClass("-ending-time");
        });

        $timetable.on("click", ".time-label", function () {
            var $this = $(this);
            if ($this.hasClass("-starting-time")) {
                $this.removeClass("-starting-time");
                $startingTime.val("");
                return;
            }
            if ($this.hasClass("-ending-time")) {
                $this.removeClass("-ending-time");
                $endingTime.val("");
                return;
            }
            var $startingLabel = $timetable.find(".-starting-time");
            var $endingLabel = $timetable.find(".-ending-time");

            if ($startingLabel.length === 0 && $endingLabel.length === 0) {
                $this.addClass("-starting-time");
                $startingTime.val($this.text());
                return;
            }

            if ($startingLabel.length === 0) {
                if ($this.text() < $endingLabel.text()) {
                    $this.addClass("-starting-time");
                    $startingTime.val($this.text());
                } else {
                    $endingLabel.removeClass("-ending-time").addClass("-starting-time");
                    $startingTime.val($endingLabel.text());
                    $this.addClass("-ending-time");
                    $endingTime.val($this.text());
                }
                return;
            }

            if ($endingLabel.length === 0) {
                if ($this.text() > $startingLabel.text()) {
                    $this.addClass("-ending-time");
                    $endingTime.val($this.text());
                } else {
                    $startingLabel.removeClass("-starting-time").addClass("-ending-time");
                    $endingTime.val($startingLabel.text());
                    $this.addClass("-starting-time");
                    $startingTime.val($this.text());
                }
                return;
            }

            if ($this.text() < $startingLabel.text()) {
                $startingLabel.removeClass("-starting-time");
                $this.addClass("-starting-time");
                $startingTime.val($this.text());
            } else {
                $endingLabel.removeClass("-ending-time");
                $this.addClass("-ending-time");
                $endingTime.val($this.text());
            }
        });

        $timetable.on("click", ".time-entry", function () {
            askConfirmation("Inclusão em passeio coletivo", "Deseja iniciar uma solicitação de agendamento para o passeio coletivo selecionado?", function () {
                console.log("yes!");
            });
        });

        $modalidade.find("select[name='modalidade']").change(function (ev) {
            var $this = $(this);
            var idModalidade = $this.val();
            var $packageOnlyFields = $modalidadeInformation.find("[data-role='package-only-fields']");
            if (!idModalidade) {
                $modalidadeInformation.addClass("hidden");
                $packageOnlyFields.addClass("hidden");
                return;
            }
            $.ajax({
                "url": "{!!route('modalidade.json.get', ['id' => '!id'])!!}".replace("!id", idModalidade),
                "type": "GET",
                "beforeSend": function () {
                    $this.addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.message);
                        return;
                    }
                    var modalidade = response.data;

                    $modalidadeInformation.find("[data-name='descricao']").text(modalidade.descricao);
                    $modalidadeInformation.find("[data-name='tipo']").text(modalidade.tipo);
                    $modalidadeInformation.find("[data-name='coletivo']").text(modalidade.coletivo ? "Sim" : "Não");
                    $modalidadeInformation.find("[data-name='precoPorCaoPorHora']").text("R$ " + modalidade.precoPorCaoPorHora.toFixed(2).replace(".", ","));
                    $modalidadeInformation.find("[data-name='periodo']").text(modalidade.periodo);
                    $modalidadeInformation.find("[data-name='frequencia']").text(modalidade.frequencia);
                    $modalidadeInformation.find("[data-name='periodo']").text(modalidade.periodo);

                    if (modalidade.pacote) {
                        $packageOnlyFields.removeClass("hidden");
                    } else {
                        $packageOnlyFields.addClass("hidden");
                    }

                    $modalidadeInformation.removeClass("hidden");
                    $modalidade.scrollView();
                },
                "error": function (request) {
                    if (request.statusText === 'abort') {
                        return;
                    }
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $this.removeClass("loading");
                }
            });
        });

        $local.find("select[name='local']").change(function (ev) {
            var $this = $(this);
            var idLocal = $this.val();
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
                    $this.addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.message);
                        return;
                    }
                    var local = response.data;

                    $localInformation.find("[data-name='imagem']").attr("src", local.thumbnail).attr("alt", local.nome);
                    $localInformation.find("[data-name='link']").attr("href", local.link).text(local.link);

                    $localInformation.removeClass("hidden");
                    $local.scrollView();
                },
                "error": function (request) {
                    if (request.statusText === 'abort') {
                        return;
                    }
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $this.removeClass("loading");
                }
            });
        });
    })();
</script>
@endsection