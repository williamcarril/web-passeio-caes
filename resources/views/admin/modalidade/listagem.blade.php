<?php
$inactiveText = trans("action.inactive");
$activeText = trans("action.active");
?>
@extends("admin.layouts.default")

@section("title") Modalidades | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Modalidades</h1>
    <div class="table-responsive">
        <a class="btn btn-default" href="{{route("admin.modalidade.novo.get")}}">
            <i class="glyphicon glyphicon-plus"></i>
            Novo
        </a>
        <table id="funcionario-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Coletivo</th>
                    <th>Período</th>
                    <th>Frequência</th>
                    <th>Preço (cão/hora)</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($modalidades as $modalidade)
                <tr data-id="{{$modalidade->idModalidade}}" class="{{!$modalidade->ativo ? "_error-color" : ""}}">
                    <td>{{$modalidade->nome}}</td>
                    <td>{{$modalidade->tipoFormatado}}</td>
                    <td>{{$modalidade->coletivoFormatado}}</td>
                    <td>{{$modalidade->periodoFormatado}}</td>
                    <td>{{$modalidade->frequenciaFormatada}}</td>
                    <td>{{$modalidade->precoPorCaoPorHoraFormatado}}</td>
                    <td data-name="ativo">{{$modalidade->ativoFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route("admin.modalidade.alterar.get", ["id" => $modalidade->idModalidade])}}" class="btn btn-default">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <button type="button" class="btn {{$modalidade->ativo ? "btn-danger" : "btn-success"}} btn-sm" data-action="change-status" data-value="{{$modalidade->ativo ? 1 : 0}}">
                                @if($modalidade->ativo)
                                {{$inactiveText}}
                                @else
                                {{$activeText}}
                                @endif
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        $("[data-action='change-status']").click(function (ev) {
            var $this = $(this);
            var $modalidade = $this.parents("[data-id]");
            var id = $modalidade.attr("data-id");
            $.ajax({
                "url": "{{route('admin.modalidade.status.post')}}",
                "type": "POST",
                "data": {
                    "id": id
                },
                "beforeSend": function () {
                    $this.addClass("loading").addClass("disabled");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.messages, "error");
                    } else {
                        switch ($this.attr("data-value")) {
                            case "0":
                                $this.attr("data-value", "1");
                                $this.text("{!!$inactiveText!!}");
                                $this.removeClass("btn-success").addClass("btn-danger");
                                $modalidade.removeClass("_error-color");
                                break;
                            case "1":
                                $this.attr("data-value", "0");
                                $this.text("{!!$activeText!!}");
                                $this.removeClass("btn-danger").addClass("btn-success");
                                $modalidade.addClass("_error-color");
                                break;
                        }
                        $modalidade.find("[data-name='ativo']").text(response.data.status);
                    }
                },
                "error": function () {
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $this.removeClass("loading").removeClass("disabled");
                }
            });
        });
    })();
</script>
@endsection