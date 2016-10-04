@extends("layouts.default")
@section("title") Passeios agendados | {{config("app.name")}} @endsection

@section("main")
<section>
    <h2>Passeios agendados (em desenvolvimento)</h2>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Horário de início</th>
                    <th>Horário de término</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($passeios as $passeio)
                <?php
                $statusClass = "";
                switch ($passeio->status) {
                    case $statusPasseio["pendente"]:
                        $statusClass = "_warning-color";
                        break;
                    case $statusPasseio["em_andamento"]:
                        $statusClass = "_warning-color";
                        break;
                    case $statusPasseio["cancelado"]:
                        $statusClass = "_error-color";
                        break;
                    case $statusPasseio["feito"]:
                        break;
                }
                ?>
                <tr class="{{$statusClass}}">
                    <td>{{$passeio->dataFormatada}}</td>
                    <td>{{$passeio->inicioFormatado}}</td>
                    <td>{{$passeio->fimFormatado}}</td>
                    <td>{{$passeio->statusFormatado}}</td>
                    <td>
                        <div class="button-group">
                            <button class="btn btn-default">
                                <i class="glyphicon glyphicon-search"></i>
                                Detalhes
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
F@endsection

@section("scripts")
@parent
@endsection