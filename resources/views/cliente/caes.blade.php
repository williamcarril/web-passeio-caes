<?php
$caes = $customer->caes;
?>
@extends("layouts.default", ["hasMap" => true])

@section("title") Cachorros | {{env("APP_NAME")}} @endsection

@section("main")
<section>
    <h1>Cachorros</h1>
    <form id="dog-form" enctype="multipart/form-data" method="POST">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Raça</th>
                        <th>Porte</th>
                        <th>Gênero</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caes as $cao)
                    <tr data-id="{{$cao->id}}" data-role="cao">
                        <td>
                            @if(!is_null($cao->imagem))
                            @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem", "image" => $cao->imagem->getUrl(), "imageDescription" => $cao->imagem->descricao])
                            @else
                            @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem"])
                            @endif
                        </td>
                        <td class="editable-label" data-action="editable-label">
                            <span data-role="label">{{$cao->nome}}</span>
                            <input name="nome" data-role="input" class="form-control" type="text" value="{{$cao->nome}}"/>
                        </td>
                        <td class="editable-label" data-action="editable-label">
                            <span data-role="label">{{$cao->raca}}</span>
                            <input name="raca" data-role="input" class="form-control" type="text" value="{{$cao->raca}}"/>
                        </td>
                        <td class="editable-label" data-action="editable-label">
                            <?php
                            switch ($cao->porte) {
                                case "pequeno":
                                    $porte = "Pequeno";
                                    break;
                                case "medio":
                                    $porte = "Médio";
                                    break;
                                case "grande":
                                    $porte = "Grande";
                                    break;
                                default:
                                    $porte = $cao->porte;
                            }
                            ?>
                            <span data-role="label">{{$porte}}</span>
                            <select data-role="input" name="porte" class="form-control">
                                <option {!!$cao->porte === "pequeno" ? "selected" : ""!!} value="pequeno">Pequeno</option>
                                <option {!!$cao->porte === "medio" ? "selected" : ""!!} value="medio">Médio</option>
                                <option {!!$cao->porte === "grande" ? "selected" : ""!!} value="grande">Grande</option>
                            </select>
                        </td>
                        <td class="editable-label" data-action="editable-label">
                            <?php
                            switch ($cao->genero) {
                                case "macho":
                                    $genero = "Macho";
                                    break;
                                case "femea":
                                    $genero = "Fêmea";
                                    break;
                                default:
                                    $porte = $cao->genero;
                            }
                            ?>
                            <span data-role="label">{{$genero}}</span>
                            <select data-role="input" name="genero" class="form-control">
                                <option {!!$cao->genero === "macho" ? "selected" : ""!!} value="macho">Macho</option>
                                <option {!!$cao->genero === "femea" ? "selected" : ""!!} value="femea">Fêmea</option>
                            </select>
                        </td>
                        <td>
                            <div class="button-group">
                                <button class="btn btn-success hidden" type="button" data-action="save-dog">
                                    <i class="glyphicon glyphicon-ok"></i>
                                </button>
                                <button class="btn btn-danger" type="button" data-action="delete-dog">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr data-id="" data-role="cao">
                        <td>
                            @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem"])
                        </td>
                        <td>
                            <input name="nome" class="form-control" type="text" value=""/>
                        </td>
                        <td>
                            <input name="raca"class="form-control" type="text" value=""/>
                        </td>
                        <td>
                            <select name="porte" class="form-control">
                                <option value="" selected>Selecione uma opção</option>
                                <option value="pequeno">Pequeno</option>
                                <option value="medio">Médio</option>
                                <option value="grande">Grande</option>
                            </select>
                        </td>
                        <td>
                            <select name="genero" class="form-control">
                                <option value="" selected>Selecione uma opção</option>
                                <option value="macho">Macho</option>
                                <option value="femea">Fêmea</option>
                            </select>
                        </td>
                        <td>
                            <div class="button-group">
                                <button class="btn btn-success" type="button" data-action="save-dog">
                                    <i class="glyphicon glyphicon-ok"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</section>
@endsection

@section("templates")
<table>
    <tbody>
        <tr data-id="" data-role="cao" data-template="new-dog">
            <td>
                @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem"])
            </td>
            <td>
                <input name="nome" class="form-control" type="text" value=""/>
            </td>
            <td>
                <input name="raca"class="form-control" type="text" value=""/>
            </td>
            <td>
                <select name="porte" class="form-control">
                    <option value="">Selecione uma opção</option>
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </td>
            <td>
                <select name="genero" class="form-control">
                    <option value="">Selecione uma opção</option>
                    <option value="macho">Macho</option>
                    <option value="femea">Fêmea</option>
                </select>
            </td>
            <td>
                <div class="button-group">
                    <button class="btn btn-success" type="button" data-action="save-dog">
                        <i class="glyphicon glyphicon-ok"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr data-id="" data-role="cao" data-template="dog">
            <td>
                @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem"])
            </td>
            <td class="editable-label" data-action="editable-label">
                <span data-role="label"></span>
                <input name="nome" data-role="input" class="form-control" type="text" value=""/>
            </td>
            <td class="editable-label" data-action="editable-label">
                <span data-role="label"></span>
                <input name="raca" data-role="input" class="form-control" type="text" value=""/>
            </td>
            <td class="editable-label" data-action="editable-label">
                <span data-role="label"></span>
                <select data-role="input" name="porte" class="form-control">
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </td>
            <td class="editable-label" data-action="editable-label">
                <span data-role="label"></span>
                <select data-role="input" name="genero" class="form-control">
                    <option value="macho">Macho</option>
                    <option value="femea">Fêmea</option>
                </select>
            </td>
            <td>
                <div class="button-group">
                    <button class="btn btn-success hidden" type="button" data-action="save-dog">
                        <i class="glyphicon glyphicon-ok"></i>
                    </button>
                    <button class="btn btn-danger" type="button" data-action="delete-dog">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $dogForm = $("#dog-form");
        var $newDogTemplate = globals.templates.find("[data-template='new-dog']");
        var $dogTemplate = globals.templates.find("[data-template='dog']");

        $dogForm.on("blur", "input[name='nome'],input[name='raca'],select[name='porte'],select[name='genero']", function () {
            var $this = $(this);
            validate.inputs.empty($this);
        });

        $dogForm.on("click", "[data-action='save-dog']", function (ev) {
            var $this = $(this);
            if ($this.hasClass("disabled")) {
                return;
            }
            var $dog = $this.parents("[data-role='cao']");
            var data = new FormData();
            data.append("id", $dog.attr("data-id"));
            data.append("nome", $dog.find("input[name='nome']").val());
            data.append("raca", $dog.find("input[name='raca']").val());
            data.append("porte", $dog.find("select[name='porte']").val());
            data.append("genero", $dog.find("select[name='genero']").val());
            data.append("imagem", $dog.find("input[name='imagem']")[0].files[0]);
            $.ajax({
                "url": "{!! route('cliente.caes.post') !!}",
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
                        // Do a lot of things...
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
