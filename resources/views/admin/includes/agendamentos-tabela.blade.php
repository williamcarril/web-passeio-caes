<?php 
$passeio = isset($passeio) ? $passeio : null;
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>E-mail</th>
            <th>Data do agendamento</th>
            <th>Hora do agendamento</th>
            <th>Modalidade</th>
            <th>Preço{{is_null($passeio) ? " (total)" : " (passeio)"}}</th>
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
            <td>{{$agendamento->cliente->telefoneFormatado}}</td>
            <td>{{$agendamento->cliente->email}}</td>
            <td>{{$agendamento->dataFormatada}}</td>
            <td>{{$agendamento->horaFormatada}}</td>
            <td>{{$agendamento->modalidade->nome}}</td>
            @if(is_null($passeio))
            <td>{{$agendamento->precoTotalFormatado}}</td>
            @else
            <td>{{$passeio->getValor($agendamento->cliente, true)}}</td>
            @endif
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