<?php
$inactiveText = trans("action.inactive");
$activeText = trans("action.active");
?>
@extends("admin.layouts.default")

@section("title") Manter cachorros de {{$cliente->nome}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Manter cachorros de {{$cliente->nome}}</h1>
    <section>
        <h2>Informações do Cliente</h2>
        <p><b>Nome: </b>{{$cliente->nome}}</p>
        <p><b>CPF: </b>{{$cliente->cpfFormatado}}</p>
        <p><b>Telefone: </b>{{$cliente->telefoneFormatado}}</p>
        <p><b>E-mail: </b>{{$cliente->email}}</p>
        <p><b>Endereço: </b>{{$cliente->getEndereco()}}</p>
        <p>
            <a class="btn btn-default" href="{{route("admin.cliente.alterar.get", ["id" => $cliente->idCliente])}}">
                <i class="glyphicon glyphicon-search"></i>
                Detalhes
            </a>
        </p>
    </section>
    <section>
        <h2>Cachorros</h2>
        <div class="table-responsive">
            <table id="dog-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Raça</th>
                        <th>Porte</th>
                        <th>Gênero</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caes as $cao)
                    <tr class="{{!$cao->ativo ? "_error-color" : ""}}" data-id="{{$cao->idCao}}" data-role="cao">
                        <td>
                            @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem", "image" => $cao->thumbnail, "imageDescription" => $cao->nome])
                        </td>
                        <td class="editable-label" data-action="editable-label" data-name="nome">
                            <span data-role="label">{{$cao->nome}}</span>
                            <input name="nome" data-role="input" class="form-control" type="text" value="{{$cao->nome}}" autocomplete="off"/>
                        </td>
                        <td class="editable-label" data-action="editable-label" data-name="raca">
                            <span data-role="label">{{$cao->raca}}</span>
                            <input name="raca" data-role="input" class="form-control" type="text" value="{{$cao->raca}}" autocomplete="off"/>
                        </td>
                        <td class="editable-label" data-action="editable-label" data-name="porte">
                            <?php
                            $porte = $cao->porteFormatado;
                            ?>
                            <span data-role="label">{{$porte}}</span>
                            <select data-role="input" name="porte" class="form-control" autocomplete="off">
                                <option {!!$cao->porte === "pequeno" ? "selected" : ""!!} value="pequeno">Pequeno</option>
                                <option {!!$cao->porte === "medio" ? "selected" : ""!!} value="medio">Médio</option>
                                <option {!!$cao->porte === "grande" ? "selected" : ""!!} value="grande">Grande</option>
                            </select>
                        </td>
                        <td class="editable-label" data-action="editable-label" data-name="genero">
                            <?php
                            $genero = $cao->generoFormatado;
                            ?>
                            <span data-role="label">{{$genero}}</span>
                            <select data-role="input" name="genero" class="form-control" autocomplete="off">
                                <option {!!$cao->genero === "macho" ? "selected" : ""!!} value="macho">Macho</option>
                                <option {!!$cao->genero === "femea" ? "selected" : ""!!} value="femea">Fêmea</option>
                            </select>
                        </td>
                        <td data-name="ativo">
                            {{$cao->ativoFormatado}}
                        </td>
                        <td>
                            <div class="button-group">
                                <button class="btn btn-success hidden" type="button" data-action="save-dog">
                                    <i class="glyphicon glyphicon-ok"></i>
                                </button>
                                <button type="button" class="btn btn-sm {{$cao->ativo ? "btn-danger" : "btn-success"}}" data-action="change-status" data-value="{{$cao->ativo ? 1 : 0}}">
                                    @if($cao->ativo)
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
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $dogTable = $("#dog-table");

        $dogTable.on("blur", "input[name='nome'],input[name='raca'],select[name='porte'],select[name='genero']", function () {
            var $this = $(this);
            $this.validate("empty");
        });

        $dogTable.on("editable-label:done", "[data-name='nome'],[data-name='raca'],[data-name='porte'],[data-name='genero']", function (ev) {
            var $this = $(this);
            var $input = $this.find("[data-role='input']");
            if (validate.empty($input.val())) {
                ev.stopPropagation();
                ev.preventDefault();
            }
            var $dog = $this.parents("[data-role='cao']");
            $dog.find("[data-action='save-dog']").removeClass("hidden");
        });
        $dogTable.on("change", "input[name='imagem']", function (ev) {
            var $this = $(this);
            var $dog = $this.parents("[data-role='cao']");
            $dog.find("[data-action='save-dog']").removeClass("hidden");
        });

        $dogTable.on("click", "[data-action='change-status']", function (ev) {
            var $this = $(this);
            if ($this.hasClass("disabled")) {
                return;
            }
            var $dog = $this.parents("[data-role='cao']");
            var id = $dog.attr("data-id");
            $.ajax({
                "url": "{!! route('admin.cliente.caes.status.post', ['id' => $cliente->idCliente]) !!}",
                "type": "POST",
                "data": {
                    "id": id
                },
                "beforeSend": function () {
                    $this.addClass("disabled").addClass("loading");
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
                                $dog.removeClass("_error-color");
                                break;
                            case "1":
                                $this.attr("data-value", "0");
                                $this.text("{!!$activeText!!}");
                                $this.removeClass("btn-danger").addClass("btn-success");
                                $dog.addClass("_error-color");
                                break;
                        }
                        $dog.find("[data-name='ativo']").text(response.data.status);
                    }
                },
                "error": function () {
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    $this.removeClass("disabled").removeClass("loading");
                }
            });
        });

        $dogTable.on("click", "[data-action='save-dog']", function (ev) {
            var $this = $(this);
            if ($this.hasClass("disabled")) {
                return;
            }
            var $dog = $this.parents("[data-role='cao']");

            var $name = $dog.find("input[name='nome']");
            var $breed = $dog.find("input[name='raca']");
            var $size = $dog.find("select[name='porte']");
            var $gender = $dog.find("select[name='genero']");

            if (!$name.val()) {
                $name.focus();
                return;
            }
            if (!$breed.val()) {
                $breed.focus();
                return;
            }
            if (!$size.val()) {
                $size.focus();
                return;
            }
            if (!$gender.val()) {
                $gender.focus();
                return;
            }

            var data = new FormData();
            data.append("id", $dog.attr("data-id"));

            data.append("nome", $name.val());
            data.append("raca", $breed.val());
            data.append("porte", $size.val());
            data.append("genero", $gender.val());

            data.append("imagem", $dog.find("input[name='imagem']")[0].files[0]);
            $.ajax({
                "url": "{!! route('admin.cliente.caes.salvar.post', ['id' => $cliente->idCliente]) !!}",
                "type": "POST",
                "processData": false,
                "contentType": false,
                "data": data,
                "beforeSend": function () {
                    $this.addClass("disabled").addClass("loading");
                },
                "success": function (response) {
                    if (!response.status) {
                        showAlert(response.messages, "error");
                        $this.removeClass("disabled");
                    } else {
                        var dog = response.data;
                        var name = dog.nome;
                        showAlert(fixArticle("!{a} " + name + " foi salv!{a} com sucesso!", dog.genero).ucfirst(), "success");

                        $this.removeClass("disabled");
                        $this.addClass("hidden");
                    }
                },
                "error": function () {
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                    $this.removeClass("disabled");
                },
                "complete": function () {
                    $this.removeClass("loading");
                }
            });
        });
    })();
</script>
@endsection
