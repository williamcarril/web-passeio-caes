@extends("layouts.default")
@section("title") Passeios agendados | {{config("app.name")}} @endsection

@section("main")
<section>
    <h2>Passeios agendados</h2>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Horário de início</th>
                    <th>Horário de término</th>
                    <th>Passeador</th>
                    <th>Porte</th>
                    <th>Preço</th>
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
                ?>
                <tr class="{{$statusClass}}">
                    <td>{{$passeio->idPasseio}}</td>
                    <td>{{$passeio->dataFormatada}}</td>
                    <td>{{$passeio->inicioFormatado}}</td>
                    <td>{{$passeio->fimFormatado}}</td>
                    <td>{{$passeio->passeadorFormatado}}</td>
                    <td>{{$passeio->porteFormatado}}</td>
                    <td>{{$passeio->getValor($customer, true)}}</td>
                    <td>{{$passeio->statusFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <a class="btn btn-default" href="{{route("cliente.passeio.detalhes.get", ["id" => $passeio->idPasseio])}}">
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
