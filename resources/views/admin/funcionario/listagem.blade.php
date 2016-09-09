<?php
$title = isset($title) ? $title : "Funcionários";
$inactiveText = trans("action.inactive");
$activeText = trans("action.active");
?>
@extends("admin.layouts.default")

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <div class="table-responsive">
        <a class="btn btn-default" href="{{route("admin.funcionario.passeador.novo.get")}}">
            <i class="glyphicon glyphicon-plus"></i>
            Novo
        </a>
        <table id="funcionario-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($funcionarios as $funcionario)
                <tr data-id="{{$funcionario->idFuncionario}}" class="{{!$funcionario->ativo ? "_error-color" : ""}}">
                    <td><img src="{{$funcionario->thumbnail}}" alt="Foto"/></td>
                    <td>{{$funcionario->nome}}</td>
                    <td>{{$funcionario->cpfFormatado}}</td>
                    <td>{{$funcionario->rg}}</td>
                    <td>{{$funcionario->telefoneFormatado}}</td>
                    <td>{{$funcionario->email}}</td>
                    <td data-name="ativo">{{$funcionario->ativoFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route("admin.funcionario.passeador.alterar.get", ["id" => $funcionario->idFuncionario])}}" class="btn btn-default">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <button type="button" class="btn {{$funcionario->ativo ? "btn-danger" : "btn-success"}}" data-action="change-status" data-value="{{$funcionario->ativo ? 1 : 0}}">
                                @if($funcionario->ativo)
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
            var $funcionario = $this.parents("[data-id]");
            var id = $funcionario.attr("data-id");
            $.ajax({
                "url": "{{route('admin.funcionario.passeador.status.post')}}",
                "type": "POST",
                "data": {
                    "id": id
                },
                "beforeSend": function () {
                    $this.addClass("loading").addClass("disabled");
                },
                "success": function (response) {
                    if(!response.status) {
                        showAlert(response.messages, "error");
                    } else {
                        switch($this.attr("data-value")) {
                            case "0":
                                $this.attr("data-value", "1");
                                $this.text("{!!$inactiveText!!}");
                                $this.removeClass("btn-success").addClass("btn-danger");
                                $funcionario.removeClass("_error-color");
                                break;
                            case "1":
                                $this.attr("data-value", "0");
                                $this.text("{!!$activeText!!}");
                                $this.removeClass("btn-danger").addClass("btn-success");
                                $funcionario.addClass("_error-color");
                                break;
                        }
                        $funcionario.find("[data-name='ativo']").text(response.data.status);
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