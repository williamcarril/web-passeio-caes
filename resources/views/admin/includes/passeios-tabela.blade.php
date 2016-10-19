<?php
$cliente = isset($cliente) ? $cliente : null;
$destaqueSemPasseadores = isset($destaqueSemPasseadores) ? $destaqueSemPasseadores : false;
$passeadores = isset($passeadores) ? $passeadores : null;
$agendamento = isset($agendamento) ? $agendamento : null;
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
            <th>Cães{{!is_null($agendamento) ? " (agendamento {$agendamento->idAgendamento} considerado)" : " confirmados"}}</th>
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
            if ($destaqueSemPasseadores && !$passeio->temPasseador() && $passeio->status === $statusPasseio["PENDENTE"]) {
                $statusClass = "_warning-color";
            }
            $caesDoPasseio = $passeio->caes;
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
                @if(!is_null($agendamento))
                <?php
                $caesDoPasseio = $caesDoPasseio->merge($agendamento->caes);
                ?>
                @endif
                <td>{{$caesDoPasseio->count()}}</td>
                <td>
                    <div class="button-group">
                        <a class="btn btn-default" href="{{route("admin.passeio.detalhes.get", ["id" => $passeio->idPasseio])}}">
                            <i class="glyphicon glyphicon-search"></i>
                            Detalhes
                        </a>
                        @if(!empty($passeadores) && !$passeio->temPasseador() && !$passeio->checarStatus([$statusPasseio["CANCELADO"], $statusPasseio["FEITO"], $statusPasseio["EM_ANDAMENTO"]]))
                        <a class="btn btn-default" data-toggle="collapse" data-target="[data-collapse='{{$idTabela}}-{{$passeio->idPasseio}}']">
                            <i class="flaticon-walker"></i>
                            Mostrar
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @if(!empty($passeadores) && !$passeio->temPasseador() && !$passeio->checarStatus([$statusPasseio["CANCELADO"], $statusPasseio["FEITO"], $statusPasseio["EM_ANDAMENTO"]]))
                @foreach($passeadores as $passeador)
                    <tr class="collapse" data-collapse="{{$idTabela}}-{{$passeio->idPasseio}}">
                        <td><b>Passeador:</b></td>
                        <td>{{$passeador->nome}}</td>
                        <td><b>Disponibilidade:</b></td>
                        <td colspan="7">
                            <?php
                            $passeadorPasseios = $passeador->passeios()->daData($passeio->data)->get();
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
                    @if($passeio->coletivo)
                    <?php
                    $limitePequenos = $passeador->getLimiteDeCaes("pequeno");
                    $limiteMedios = $passeador->getLimiteDeCaes("medio");
                    $limiteGrandes = $passeador->getLimiteDeCaes("grande");
                    $indicativeClass = "";
                    switch ($passeio->porte) {
                        case "pequeno":
                            $limiteVerificado = $limitePequenos;
                            break;
                        case "medio":
                            $limiteVerificado = $limiteMedios;
                            break;
                        case "grande":
                            $limiteVerificado = $limiteGrandes;
                            break;
                    }
                    if (is_null($limiteVerificado)) {
                        $indicativeClass .= "_warning-color";
                    } elseif ($limiteVerificado < $caesDoPasseio->count()) {
                        $indicativeClass .= "_error-color";
                    } else {
                        $indicativeClass .= "_success-color";
                    }
                    ?>
                    <tr class="collapse" data-collapse="{{$idTabela}}-{{$passeio->idPasseio}}">
                        <td colspan="4"><b>Limite de cães:</b></td>
                        <td colspan="2" class="{{$passeio->porte === "pequeno" ? $indicativeClass : ""}}">Pequenos: {{!is_null($limitePequenos) ? $limitePequenos : "Não definido"}}</td>
                        <td colspan="2" class="{{$passeio->porte === "medio" ? $indicativeClass : ""}}">Médios: {{!is_null($limiteMedios) ? $limiteMedios : "Não definido"}}</td>
                        <td colspan="2" class="{{$passeio->porte === "grande" ? $indicativeClass : ""}}">Grandes: {{!is_null($limiteGrandes) ? $limiteGrandes : "Não definido"}}</td>
                    </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
