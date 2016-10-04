@extends("admin.layouts.default")

@section("main")
<section>
    <h1>Agendamentos</h1>
    <div class="table-responsive">
        <form class="form-inline  pull-right" action="{{route("admin.agendamento.listagem.get")}}" method="GET">
            <div class="form-group">
                <label for="filtro-agendamento">Filtro por status:</label>
                <select id="filtro-agendamento" class="form-control" name="filtro">
                    <option {!!empty($filtro) ? "selected" : ""!!} value="">Selecione uma opção</option>
                    <option {!!$filtro == "pendente_funcionario" ? "selected" : ""!!} value="FUNCIONARIO">Apenas pendentes por funcionários</option>
                    <option {!!$filtro == "pendente_cliente" ? "selected" : ""!!} value="CLIENTE">Apenas pendentes por clientes</option>
                    <option {!!$filtro == "feitos" ? "selected" : ""!!} value="FEITO">Apenas confirmados</option>
                    <option {!!$filtro == "cancelados" ? "selected" : ""!!} value="CANCELADO">Apenas cancelados</option>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Filtrar</button>
        </form>
        <table id="agendamento-table" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data do agendamento</th>
                    <th>Hora do agendamento</th>
                    <th>Modalidade</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($agendamentos as $agendamento)
                <?php
                $statusClass = "";
                switch ($agendamento->status) {
                    case $statusAgendamento["CANCELADO"]:
                        $statusClass = "_error-color";
                        break;
                    case $statusAgendamento["FEITO"]:
                        $statusClass = "";
                        break;
                    case $statusAgendamento["CLIENTE"]:
                    case $statusAgendamento["FUNCIONARIO"]:
                        $statusClass = "_warning-color";
                        break;
                }
                ?>
                <tr class="{{$statusClass}}">
                    <td>{{$agendamento->idAgendamento}}</td>
                    <td>{{$agendamento->cliente->nome}}</td>
                    <td>{{$agendamento->dataFormatada}}</td>
                    <td>{{$agendamento->horaFormatada}}</td>
                    <td>{{$agendamento->modalidade->nome}}</td>
                    <td>{{$agendamento->precoTotalFormatado}}</td>
                    <td>{{$agendamento->statusFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route('admin.agendamento.detalhes.get', ["id" => $agendamento->idAgendamento])}}" class="btn btn-default">
                                <i class="glyphicon glyphicon-search"></i>
                                Detalhes
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