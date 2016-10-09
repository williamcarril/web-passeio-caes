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
                    <option {!!$filtro == "FUNCIONARIO" ? "selected" : ""!!} value="FUNCIONARIO">Apenas pendentes por funcionários</option>
                    <option {!!$filtro == "CLIENTE" ? "selected" : ""!!} value="CLIENTE">Apenas pendentes por clientes</option>
                    <option {!!$filtro == "FEITO" ? "selected" : ""!!} value="FEITO">Apenas confirmados</option>
                    <option {!!$filtro == "CANCELADO" ? "selected" : ""!!} value="CANCELADO">Apenas cancelados</option>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Filtrar</button>
        </form>
        @include("admin.includes.agendamentos-tabela", ["agendamentos" => $agendamentos])
    </div>
</section>
@endsection