<?php
$nonClickable = isset($nonClickable) ? $nonClickable : true;

$businessStartingTime = isset($businessStartingTime) ? $businessStartingTime : config("general.businessTime.start");
$businessEndingTime = isset($businessEndingTime) ? $businessEndingTime : config("general.businessTime.end");

$locais = collect();
$passeios = $passeios->map(function($passeio) use ($locais) {
    $arr = $passeio->toArray();
    $arr["local"] = $passeio->local->nome;
    $arr["tipo"] = $passeio->tipo;
    $locais[$passeio->local->idLocal] = $passeio->local->nome;
    return $arr;
});
$locais = array_values($locais->toArray());
$id = isset($id) ? $id : uniqid("timetable");

$inicio = isset($inicio) ? $inicio : null;
$fim = isset($fim) ? $fim : null;
$rotulo = isset($rotulo) ? $rotulo : "";
$classe = isset($classe) ? $classe : "";
?>
<div id="{{$id}}" class="timetable {{$nonClickable ? "-non-clicable" : ""}}"></div>

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var locais = JSON.parse('{!! json_encode($locais) !!}');
        var passeios = JSON.parse('{!! json_encode($passeios) !!}');

        var timetable = new Timetable();
        timetable.setScope(parseInt("{!!$businessStartingTime!!}"), parseInt("{!!$businessEndingTime!!}"));
        var renderer = new Timetable.Renderer(timetable);

        timetable.addLocations(locais);
        var passeio, inicio, fim, data;
        for (var i in passeios) {
            passeio = passeios[i];
            data = passeio.data;
            inicio = new Date(passeio.data + " " + passeio.inicio);
            fim = new Date(passeio.data + " " + passeio.fim);

            timetable.addEvent(passeio.tipo, passeio.local, inicio, fim);
        }
        @if(!empty($inicio) && !empty($fim)) 
        for(var i in locais) {
            local = locais[i];
            inicio = new Date(data + " {!! $inicio !!}");
            fim = new Date(data + " {!! $fim !!}");
            timetable.addEvent("{!! $rotulo !!}", local, inicio, fim, {
                "class": "{!! $classe !!}"
            });
        }
        @endif
        renderer.draw('#{!! $id !!}'); // any css selector
    })();
</script>
@endsection