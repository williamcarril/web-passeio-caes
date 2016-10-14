<?php
$inactiveText = trans("action.inactive");
$activeText = trans("action.active");
?>
@extends("admin.layouts.default")

@section("title") Clientes | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Clientes</h1>
    <div class="table-responsive">
        <table id="funcionario-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr data-id="{{$cliente->idCliente}}" class="{{!$cliente->ativo ? "_error-color" : ""}}">
                    <td>{{$cliente->nome}}</td>
                    <td>{{$cliente->cpfFormatado}}</td>
                    <td>{{$cliente->telefoneFormatado}}</td>
                    <td>{{$cliente->email}}</td>
                    <td>{{$cliente->getEndereco()}}</td>
                    <td data-name="ativo">{{$cliente->ativoFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route("admin.cliente.alterar.get", ["id" => $cliente->idCliente])}}" class="btn btn-default">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <a href="{{route("admin.cliente.caes.manter.get", ["id" => $cliente->idCliente])}}" class="btn btn-default">
                                <i class="flaticon-dog"></i>
                            </a>
                            <button type="button" class="btn btn-sm {{$cliente->ativo ? "btn-danger" : "btn-success"}}" data-action="change-status" data-value="{{$cliente->ativo ? 1 : 0}}">
                                @if($cliente->ativo)
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
            var $cliente = $this.parents("[data-id]");
            var id = $cliente.attr("data-id");
            $.ajax({
                "url": "{{route('admin.cliente.status.post')}}",
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
                                $cliente.removeClass("_error-color");
                                break;
                            case "1":
                                $this.attr("data-value", "0");
                                $this.text("{!!$activeText!!}");
                                $this.removeClass("btn-danger").addClass("btn-success");
                                $cliente.addClass("_error-color");
                                break;
                        }
                        $cliente.find("[data-name='ativo']").text(response.data.status);
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