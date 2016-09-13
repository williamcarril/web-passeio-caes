@extends("layouts.default")

@section("title") Cachorros | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Cachorros</h1>
    <div class="table-responsive">
        <table id="dog-table" class="table table-striped table-hover">
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
                <tr data-id="{{$cao->idCao}}" data-role="cao">
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
                        <select data-role="input" name="porte" class="form-control" autocomplete="off">
                            <option {!!$cao->porte === "pequeno" ? "selected" : ""!!} value="pequeno">Pequeno</option>
                            <option {!!$cao->porte === "medio" ? "selected" : ""!!} value="medio">Médio</option>
                            <option {!!$cao->porte === "grande" ? "selected" : ""!!} value="grande">Grande</option>
                        </select>
                    </td>
                    <td class="editable-label" data-action="editable-label" data-name="genero">
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
                        <select data-role="input" name="genero" class="form-control" autocomplete="off">
                            <option {!!$cao->genero === "macho" ? "selected" : ""!!} value="macho">Macho</option>
                            <option {!!$cao->genero === "femea" ? "selected" : ""!!} value="femea">Fêmea</option>
                        </select>
                    </td>
                    <td>
                        <div class="button-group">
                            <button class="btn btn-success hidden" type="button" data-action="save-dog">
                                <i class="glyphicon glyphicon-ok"></i>
                            </button>
                            <a href="{{route("cliente.caes.vacina.get", ["id" => $cao->idCao])}}" class="btn btn-default">
                                <i class="flaticon-medical"></i>
                            </a>
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
                        <input name="nome" class="form-control" type="text" value="" autocomplete="off"/>
                    </td>
                    <td>
                        <input name="raca"class="form-control" type="text" value="" autocomplete="off"/>
                    </td>
                    <td>
                        <select name="porte" class="form-control" autocomplete="off">
                            <option value="" selected>Selecione uma opção</option>
                            <option value="pequeno">Pequeno</option>
                            <option value="medio">Médio</option>
                            <option value="grande">Grande</option>
                        </select>
                    </td>
                    <td>
                        <select name="genero" class="form-control" autocomplete="off">
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
                <input name="nome" class="form-control" type="text" value="" autocomplete="off"/>
            </td>
            <td>
                <input name="raca"class="form-control" type="text" value="" autocomplete="off"/>
            </td>
            <td>
                <select name="porte" class="form-control" autocomplete="off">
                    <option value="">Selecione uma opção</option>
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </td>
            <td>
                <select name="genero" class="form-control" autocomplete="off">
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
            <td data-name="imagem">
                @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem"])
            </td>
            <td class="editable-label" data-action="editable-label" data-name="nome">
                <span data-role="label"></span>
                <input name="nome" data-role="input" class="form-control" type="text" value="" autocomplete="off"/>
            </td>
            <td class="editable-label" data-action="editable-label" data-name="raca">
                <span data-role="label"></span>
                <input name="raca" data-role="input" class="form-control" type="text" value="" autocomplete="off"/>
            </td>
            <td class="editable-label" data-action="editable-label" data-name="porte">
                <span data-role="label"></span>
                <select data-role="input" name="porte" class="form-control" autocomplete="off">
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </td>
            <td class="editable-label" data-action="editable-label" data-name="genero">
                <span data-role="label"></span>
                <select data-role="input" name="genero" class="form-control" autocomplete="off">
                    <option value="macho">Macho</option>
                    <option value="femea">Fêmea</option>
                </select>
            </td>
            <td>
                <div class="button-group">
                    <button class="btn btn-success hidden" type="button" data-action="save-dog">
                        <i class="glyphicon glyphicon-ok"></i>
                    </button>
                    <a href="{{route("cliente.caes.vacina.get", ["id" => "!id"])}}" class="btn btn-default" data-role="vaccination-link">
                        <i class="flaticon-medical"></i>
                    </a>
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
        var $dogTable = $("#dog-table");
        var $newDogTemplate = globals.templates.find("[data-template='new-dog']");
        var $dogTemplate = globals.templates.find("[data-template='dog']");

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
        $dogTable.on("change", "input[name='imagem']", function(ev) {
            var $this = $(this);
            var $dog = $this.parents("[data-role='cao']");
            $dog.find("[data-action='save-dog']").removeClass("hidden");
        });

        $dogTable.on("click", "[data-action='delete-dog']", function (ev) {
            var $this = $(this);
            if ($this.hasClass("disabled")) {
                return;
            }
            var $dog = $this.parents("[data-role='cao']");
            var id = $dog.attr("data-id");
            var name = $dog.find("input[name='nome']").val();
            var gender = $dog.find("select[name='genero']").val();
            askConfirmation("Remover cachorro", fixArticle("Deseja mesmo remover !{a} " + name + "?", gender), function () {
                $.ajax({
                    "url": "{!! route('cliente.caes.delete.post') !!}",
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
                            $this.removeClass("disabled");
                        } else {
                            showAlert(fixArticle("!{a} " + name + " foi removid!{a} com sucesso!", gender).ucfirst(), "success");
                            $dog.remove();
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
                        var dog = response.data;
                        var name = dog.nome;
                        showAlert(fixArticle("!{a} " + name + " foi salv!{a} com sucesso!", dog.genero).ucfirst(), "success");
                        //Caso tenha sido uma alteração...
                        if ($dog.attr("data-id")) {
                            $this.removeClass("disabled");
                            $this.addClass("hidden");
                            return;
                        }

                        //Caso tenha sido uma inclusão...
                        var $dogHtml = $dogTemplate.clone();
                        var $newDogHtml = $newDogTemplate.clone();

                        $dogHtml.attr("data-id", dog.idCao);
                        $dogHtml.find("[data-name='nome']").editableLabel("fill", dog.nome);
                        $dogHtml.find("[data-name='raca']").editableLabel("fill", dog.raca);
                        $dogHtml.find("[data-name='genero']").editableLabel("fill", dog.genero);
                        $dogHtml.find("[data-name='porte']").editableLabel("fill", dog.porte);

                        var $image = $dogHtml.find("[data-name='imagem']").find("[data-action='image-uploader']");
                        $image.imageUploader("preview", dog.thumbnail);
                        $image.imageUploader("id", uniqid());

                        $dogHtml.find("[data-role='vaccination-link']").attr("href", function () {
                            var $this = $(this);
                            return $this.attr("href").replace("!id", dog.idCao);
                        });

                        $dogHtml.removeAttr("data-template");
                        $newDogHtml.removeAttr("data-template");
                        $dog.remove();
                        $dogTable.children("tbody").append($dogHtml).append($newDogHtml);
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
