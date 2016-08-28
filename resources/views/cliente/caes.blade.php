<?php
$caes = $customer->caes;
?>
@extends("layouts.default", ["hasMap" => true])

@section("title") Cachorros | {{env("APP_NAME")}} @endsection

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
                        <span data-role="label">{{$cao->porte}}</span>
                        <select data-role="input" name="porte" class="form-control">
                            <option value="pequeno">Pequeno</option>
                            <option value="medio">Médio</option>
                            <option value="grande">Grande</option>
                        </select>
                    </td>
                    <td class="editable-label" data-action="editable-label">
                        <span data-role="label">{{$cao->porte}}</span>
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
                @endforeach
                <tr data-role="cao">
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
        <button class='btn btn-default pull-right'>Salvar</button>
    </div>
</section>
@endsection

@section("templates")
<table>
    <tbody>
        <tr data-role="cao" data-template="new-dog">
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
        var $dogTable = $("#dog-table");
        var $newDogTemplate = globals.templates.find("[data-template='new-dog']");
        var $dogTemplate = globals.templates.find("[data-template='dog']");

        $dogTable.on("blur", "input[name='nome'],input[name='raca'],select[name='porte'],select[name='genero']", function () {
            var $this = $(this);
            validate.inputs.empty($this);
        });

        //Parei aqui
        $dogTable.on("click", "[data-action='save-dog']", function (ev) {
            var $this = $(this);
            var $dog = $this.parents("[data-role='cao']");
            var id = $dog.attr("data-id").val();
            var nome = $dog.find("input[name='nome']").val();
            var raca = $dog.find("input[name='raca']").val();
            var genero = $dog.find("input[name='genero']").val();
            var porte = $dog.find("input[name='porte']").val();
            console.log([nome,raca,genero,porte]);
            $.ajax({
                "url": "{!! route('cliente.caes.post') !!}",
                "type": "POST",
                "process": false,
                "data": {
                    "id": id,
                    "nome": nome,
                    "raca": raca,
                    "porte": porte,
                    "genero": genero
                },
                "beforeSend": function () {
                    loadingAnimation(true);
                },
                "success": function (response) {

                },
                "error": function () {
                    showAlert("{!!trans('alert.error.request')!!}", "error");
                },
                "complete": function () {
                    loadingAnimation(false);
                }
            });
        });
    })();
</script>
@endsection
