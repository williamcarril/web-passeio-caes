@extends("admin.layouts.default")

@section("main")
<section>
    <h1>Passeios</h1>
    <div class="table-responsive">
        <form class="form-inline  pull-right" action="{{route("admin.passeio.marcados.listagem.get")}}" method="GET">
            <div class="form-group">
                <label for="filtro-datas">Filtrar por datas:</label>
                <input value="{{!empty($dataInicial) ? $dataInicial : ""}}" class="form-control" name="dataInicial" type="text" data-inputmask="'mask': '99/99/9999'"> até
                <input value="{{!empty($dataFinal) ? $dataFinal : ""}}" class="form-control" name="dataFinal" type="text" data-inputmask="'mask': '99/99/9999'">
            </div>
            <div class="form-group">
                <label for="filtro-agendamento">Filtro por status:</label>
                <select id="filtro-agendamento" class="form-control" name="status">
                    <option {!!empty($status) ? "selected" : ""!!} value="">Selecione uma opção</option>
                    <option {!!$status == "em_analise" ? "selected" : ""!!} value="em_analise">Apenas em análise</option>
                    <option {!!$status == "pendente" ? "selected" : ""!!} value="pendente">Apenas pendentes</option>
                    <option {!!$status == "em_andamento" ? "selected" : ""!!} value="em_andamento">Apenas em andamento</option>
                    <option {!!$status == "feito" ? "selected" : ""!!} value="feito">Apenas realizados</option>
                    <option {!!$status == "cancelado" ? "selected" : ""!!} value="cancelado">Apenas cancelados</option>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Filtrar</button>
        </form>
        <hr/>
        @include("admin.includes.passeios-tabela", ["passeios" => $passeios, "destaqueSemPasseadores" => true])
    </div>
</section>
@endsection