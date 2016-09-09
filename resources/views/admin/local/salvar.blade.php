<?php
$title = isset($title) ? $title : "Local";
?>    

@extends("admin.layouts.default", ["hasMap" => true])

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <form id="form-manter-local" role="form" method="POST" action="{{route("admin.local.salvar.post")}}" enctype="multpart/form-data">
        {!! csrf_field() !!}
        @if(!empty($local))
        <input type="hidden" name="id" value="{{$local->idLocal}}"/>
        @endif
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="local-nome">Nome *</label>
                <input tabindex='1' value="{{!empty($local->nome) ? $local->nome : ""}}" required name="nome" id="local-nome" type="text" class="form-control" placeholder="Informe o nome do local">
            </div>
        </fieldset>
        <fieldset>
            <legend>Fotos</legend>
            <div class="form-group">
            </div>
        </fieldset>
        <?php
        $mapFieldsetData = [
            "idPrefix" => "local",
            "values" => !empty($local) ? $local : null,
            "required" => [
                "numero" => false
            ]
        ];
        ?>
        @include("includes.form.map-fieldset", $mapFieldsetData)
        <button type="submit" class="btn btn-default btn-lg pull-right">Salvar</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $form = $("#form-manter-local");
        @if(!empty($ignoreOnChecks))
            var ignoreOnChecks = $.parseJSON('{!! json_encode($ignoreOnChecks) !!}');
        @else
            var ignoreOnChecks = {};
        @endif
        $form.on("blur", "input[name='nome']", function (ev) {
            var $this = $(this);
            var nome = $this.val();
            if (validate.empty(nome)) {
                setInputStatus($this, "error");
                return;
            }
            $this.ajaxValidation(
                "{!!route('admin.local.check.nome.get')!!}",
                "GET",
                {"nome": nome, "ignore": ignoreOnChecks.nome || null},
                "O nome informado já está sendo utilizado."
            );
        });
        $form.defaultAjaxSubmit("{!! route('admin.local.listagem.get') !!}");
    })();
</script>
@endsection