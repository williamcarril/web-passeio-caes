<?php 
$marcarVistoButton = isset($marcarVistoButton) ? $marcarVistoButton : false;
?>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Hora</th>
            <th>Solicitante</th>
            <th>Tipo de solicitante</th>
            <th>Justificativa</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cancelamentos as $cancelamento)
        <tr data-id="{{$cancelamento->idCancelamento}}" data-role="cancelamento" class="{{$cancelamento->status === $statusCancelamento["PENDENTE"] ? "_warning-color" : ""}}">
            <td>{{$cancelamento->idCancelamento}}</td>
            <td>{{$cancelamento->dataFormatada}}</td>
            <td>{{$cancelamento->horaFormatada}}</td>
            <td>{{$cancelamento->pessoa->nome}}</td>
            <td>{{$cancelamento->tipoSolicitanteFormatado}}</td>
            <td>{{str_limit($cancelamento->justificativa, 15)}}</td>
            <td>{{$cancelamento->statusFormatado}}</td>
            <td>
                <div class="button-group">
                    <a href="{{route("admin.cancelamento.detalhes.get", ["id" => $cancelamento->idCancelamento])}}" class="btn btn-default">
                        <i class="glyphicon glyphicon-search"></i>
                        Detalhes
                    </a>
                    @if($marcarVistoButton && $cancelamento->status !== $statusCancelamento["VERIFICADO"])
                    <button class="btn btn-success" data-action="marcar-visto">
                        <i class="glyphicon glyphicon-eye-open"></i>
                        Visto
                    </button>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>