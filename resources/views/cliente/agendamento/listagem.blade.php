@extends("layouts.default")
@section("title") Agendamentos realizados | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>
        Agendamentos realizados
    </h1>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modalidade</th>
                    <th>Data do agendamento</th>
                    <th>Hora do agendamento</th>
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
                    <td>{{$agendamento->modalidade->nome}}</td>
                    <td>{{$agendamento->dataFormatada}}</td>
                    <td>{{$agendamento->horaFormatada}}</td>
                    <td>{{$agendamento->precoTotalFormatado}}</td>
                    <td>{{$agendamento->statusFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a href="{{route('cliente.agendamento.detalhes.get', ["id" => $agendamento->idAgendamento])}}" class="btn btn-default">
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