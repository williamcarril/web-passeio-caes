<?php
$title = isset($title) ? $title : "Funcionários";
?>
@extends("admin.layouts.default")

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <div class="table-responsive">
        <table id="funcionario-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Ativo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($funcionarios as $funcionario)
                <tr class="{{!$funcionario->ativo ? ".bg-danger" : ""}}">
                    <td><img src="{{$funcionario->thumbnail}}" alt="Foto"/></td>
                    <td>{{$funcionario->nome}}</td>
                    <td>{{$funcionario->cpfFormatado}}</td>
                    <td>{{$funcionario->rg}}</td>
                    <td>{{$funcionario->telefoneFormatado}}</td>
                    <td>{{$funcionario->email}}</td>
                    <td>{{$funcionario->ativoFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="#" class="btn btn-default">
                                <i class="glyphicon glyphicon-search"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection