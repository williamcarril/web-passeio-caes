@extends("layouts.default", ["hasMap" => true])

@section("title") Cachorros | {{env("APP_NAME")}} @endsection

@section("main")
<section>
    <h1>Cachorros</h1>
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
                <tr>
                    <td>
                        @include("includes.image-uploader", ["placeholder" => false, "icon" => false])
                    </td>
                    <td class="editable-label" data-action="editable-label">
                        <span data-role="label">Teste</span>
                        <input data-role="input" class="form-control" type="text" value="Teste" />
                    </td>
                    <td><input class="form-control" type="text" /></td>
                    <td>
                        <select class="form-control">
                            <option>Pequeno</option>
                            <option>Médio</option>
                            <option>Grande</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control">
                            <option>Macho</option>
                            <option>Fêmea</option>
                        </select>
                    </td>
                    <td>
                        <div class="button-group">
                            <button class="btn btn-success" type="button">
                                <i class="glyphicon glyphicon-ok"></i>
                            </button>
                            <button class="btn btn-danger" type="button">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection

@section("scripts")
@parent
@endsection