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

@section("title") Agenda de passeios | {{config("app.name")}} @endsection

@section("main")
<section>
    @include("includes.calendar", ["id" => "agenda", "events" => $events])
    <div id="horarios" class="timetable">

    </div>
</section>
@endsection

@section("scripts")
<script type="text/javascript">
    (function () {
        var $agenda = $("#agenda");
        var $horarios = $("#horarios");
        var url = "{!! route('passeio.data.json.get', ['dia' => '!dia', 'mes' => '!mes', 'ano' => '!ano']) !!}";
        var urlSemDia = "{!! route('passeio.data.json.get', ['mes' => '!mes', 'ano' => '!ano']) !!}";
        var dayClickAjax = null;
        
        $agenda.responsiveCalendar({
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
                        $horarios.fadeOut(400);
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
                        $agenda.responsiveCalendar("edit", events);
                    },
                    "error": function (request) {
                        if (request.statusText === 'abort') {
                            return;
                        }
                        showAlert("Ocorreu um problema ao enviar a requisição. Por favor, atualize a página ou tente novamente mais tarde.", "error");
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
                        $horarios.fadeOut(400);
                    },
                    "success": function (response) {
                        if (!response.status) {
                            return;
                        }
                        var timetable = new Timetable();
                        timetable.setScope(parseInt("{!!$businessTimeStart!!}"), parseInt("{!!$businessTimeEnd!!}"));
                        var renderer = new Timetable.Renderer(timetable);

                        timetable.addLocations(response.data.locais);
                        var passeio, inicio, fim;
                        for (var i in response.data.passeios) {
                            passeio = response.data.passeios[i];
                            inicio = new Date(passeio.data + " " + passeio.inicio);
                            fim = new Date(passeio.data + " " + passeio.fim);

                            timetable.addEvent(passeio.modalidade, passeio.local, inicio, fim);
                        }
                        renderer.draw('#horarios'); // any css selector
                        $horarios.fadeIn(400);
                    },
                    "error": function (request) {
                        if (request.statusText === 'abort') {
                            return;
                        }
                        showAlert("Ocorreu um problema ao enviar a requisição. Por favor, atualize a página ou tente novamente mais tarde.", "error");
                    },
                    "complete": function () {
                        dayClickAjax = null;
                    }
                });
            }
        });
    })();
</script>
@endsection