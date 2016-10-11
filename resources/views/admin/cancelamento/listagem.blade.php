@extends("admin.layouts.default")

@section("main")
<section>
    <h1>Cancelamentos</h1>
    <div class="table-responsive">
        <form class="form-inline  pull-right" action="{{route("admin.cancelamento.listagem.get")}}" method="GET">
            <div class="form-group">
                <label for="filtro-agendamento">Filtro por tipo de solicitante:</label>
                <select id="filtro-agendamento" class="form-control" name="solicitante">
                    <option {!!empty($solicitante) ? "selected" : ""!!} value="">Selecione uma opção</option>
                    <option {!!$solicitante == "administrador" ? "selected" : ""!!} value="administrador">Apenas administradores</option>
                    <option {!!$solicitante == "passeador" ? "selected" : ""!!} value="passeador">Apenas passeadores</option>
                    <option {!!$solicitante == "cliente" ? "selected" : ""!!} value="cliente">Apenas clientes</option>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Filtrar</button>
        </form>
        @include("admin.includes.cancelamentos-tabela",["cancelamentos" => $cancelamentos, "statusCancelamento" => $statusCancelamento, "marcarVistoButton" => true])
    </div>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        $("[data-action='marcar-visto']").on("click", function () {
            var idCancelamento = $(this).parents("[data-role='cancelamento']").attr("data-id");
            $(this).defaultAjaxCall(
                    "{!! route('admin.cancelamento.visto.post', ['id' => '!id']) !!}".replace("!id", idCancelamento),
                    "POST",
                    "{!! route('admin.cancelamento.listagem.get') !!}"
                    );
        });
    })();
</script>
@endsection