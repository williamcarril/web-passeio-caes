<?php 
$cliente = isset($cliente) ? $cliente : null;
$destaqueSemPasseadores = isset($destaqueSemPasseadores) ? $destaqueSemPasseadores : false;
$passeadores = isset($passeadores) ? $passeadores : null;
$idTabela = uniqid();
?>
<table id="{{$idTabela}}" class="table table-hover table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Horário de início</th>
            <th>Horário de término</th>
            <th>Passeador</th>
            <th>Porte</th>
            <th>Preço{{is_null($cliente) ? " (total)" : ""}}</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($passeios as $passeio)
        <?php
        $statusClass = "";
        switch ($passeio->status) {
            case $statusPasseio["PENDENTE"]:
                break;
            case $statusPasseio["EM_ANDAMENTO"]:
            case $statusPasseio["EM_ANALISE"]:
                $statusClass = "_warning-color";
                break;
            case $statusPasseio["CANCELADO"]:
                $statusClass = "_error-color";
                break;
            case $statusPasseio["FEITO"]:
                $statusClass = "_success-color";
                break;
        }
        if($destaqueSemPasseadores && !$passeio->temPasseador()) {
            $statusClass = "_warning-color";
        }
        ?>
        <tr class="{{$statusClass}}">
            <td>{{$passeio->idPasseio}}</td>
            <td>{{$passeio->dataFormatada}}</td>
            <td>{{$passeio->inicioFormatado}}</td>
            <td>{{$passeio->fimFormatado}}</td>
            <td>{{$passeio->passeadorFormatado}}</td>
            <td>{{$passeio->porteFormatado}}</td>
            <td>{{$passeio->getValor($cliente, true)}}</td>
            <td>{{$passeio->statusFormatado}}</td>
            <td>
                <div class="button-group">
                    <a class="btn btn-default" href="{{route("admin.passeio.detalhes.get", ["id" => $passeio->idPasseio])}}">
                        <i class="glyphicon glyphicon-search"></i>
                        Detalhes
                    </a>
                    @if(!empty($passeadores) && !$passeio->temPasseador())
                    <a class="btn btn-default" data-toggle="collapse" data-target="[data-collapse='{{$idTabela}}-{{$passeio->idPasseio}}']">
                        <i class="flaticon-walker"></i>
                        Mostrar
                    </a>
                    @endif
                </div>
            </td>
        </tr>
        @if(!empty($passeadores) && !$passeio->temPasseador())
            @foreach($passeadores as $passeador)
            <tr class="collapse" data-collapse="{{$idTabela}}-{{$passeio->idPasseio}}">
                <td><b>Passeador:</b></td>
                <td>{{$passeador->nome}}</td>
                <td><b>Disponibilidade:</b></td>
                <td colspan="6">
                    <?php
                    $passeadorPasseios = $passeador->obterPasseiosDaData($passeio->data);
                    $options = [
                        "inicio" => $passeio->inicio,
                        "fim" => $passeio->fim,
                        "passeios" => $passeadorPasseios,
                        "classe" => $passeador->conflitaComSeusPasseios($passeio) ? "_error-background" : "_success-background"
                    ];
                    ?>
                    @if($passeadorPasseios->count() === 0)
                    <span class="_success-color">Total</span>
                    @else
                    @include("includes.timetable", $options)
                    @endif
                </td>
            </tr>
            @endforeach
        @endif
        @endforeach
    </tbody>
</table>
