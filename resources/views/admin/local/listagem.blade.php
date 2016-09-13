<?php
$inactiveText = trans("action.inactive");
$activeText = trans("action.active");
?>
@extends("admin.layouts.default")

@section("title") Locais de Passeio | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Locais de Passeio</h1>
    <div class="table-responsive">
        <a class="btn btn-default" href="{{route("admin.local.novo.get")}}">
            <i class="glyphicon glyphicon-plus"></i>
            Novo
        </a>
        <table id="funcionario-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locais as $local)
                <tr data-id="{{$local->idLocal}}" class="{{!$local->ativo ? "_error-color" : ""}}">
                    <td><img src="{{$local->thumbnail}}" alt="Foto"/></td>
                    <td>{{$local->nome}}</td>
                    <td>{{$local->getEndereco(["logradouro", "bairro"])}}</td>
                    <td data-name="ativo">{{$local->ativoFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route("admin.local.alterar.get", ["id" => $local->idLocal])}}" class="btn btn-default">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm {{$local->ativo ? "btn-danger" : "btn-success"}}" data-action="change-status" data-value="{{$local->ativo ? 1 : 0}}">
                                @if($local->ativo)
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
            var $local = $this.parents("[data-id]");
            var id = $local.attr("data-id");
            $.ajax({
                "url": "{{route('admin.local.status.post')}}",
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
                                $local.removeClass("_error-color");
                                break;
                            case "1":
                                $this.attr("data-value", "0");
                                $this.text("{!!$activeText!!}");
                                $this.removeClass("btn-danger").addClass("btn-success");
                                $local.addClass("_error-color");
                                break;
                        }
                        $local.find("[data-name='ativo']").text(response.data.status);
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