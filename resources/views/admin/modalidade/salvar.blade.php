<?php
$title = isset($title) ? $title : "Modalidade";
?>    

@extends("admin.layouts.default")

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <form id="form-manter-modalidade" role="form" method="POST" action="{{route("admin.modalidade.salvar.post")}}">
        {!! csrf_field() !!}
        @if(!empty($modalidade))
        <input type="hidden" name="id" value="{{$modalidade->idModalidade}}"/>
        @endif
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="modalidade-nome">Nome *</label>
                <input {!! (!empty($modalidade) && $modalidade->eModalidadeBase() ? "disabled": "") !!} tabindex='1' value="{{!empty($modalidade->nome) ? $modalidade->nome : ""}}" required name="nome" id="modalidade-nome" type="text" class="form-control" placeholder="Informe o nome do modalidade">
            </div>
            <div class="form-group">
                <label class="control-label" for="modalidade-descricao">Descrição *</label>
                <textarea id="modalidade-descricao" class="form-control" tabindex='3' name="descricao" placeholder="Informe a descrição do modalidade">{{!empty($modalidade->descricao) ? $modalidade->descricao : ""}}</textarea>
            </div>
            <div class="form-group">
                <label class="control-label" for="modalidade-tipo">Tipo *</label>
                <select {!! (!empty($modalidade) && $modalidade->eModalidadeBase() ? "disabled": "") !!} tabindex="3" id="modalidade-tipo" class="form-control" name="tipo">
                    @if(empty($modalidade))
                    <option value="">Selecione um tipo</option>
                    @endif
                    @foreach($tipos as $tipo)
                    <option {{(!empty($modalidade) && ($modalidade->tipo === $tipo["value"])) ? "selected" : ""}} value="{{$tipo["value"]}}">{{$tipo["text"]}}</option>
                    @endforeach
                </select>
            </div>
            <?php 
            $hasPackageFields = (!empty($modalidade) && $modalidade->tipo === "pacote");
            ?>
            <div class="{{$hasPackageFields ? "" : "hidden"}}" data-role="package-related-fields">
                <div class="form-group">
                    <label class="control-label" for="modalidade-periodo">Período *</label>
                    <select {!! (!empty($modalidade) && $modalidade->eModalidadeBase() ? "disabled": "") !!} {{$hasPackageFields ? "" : "disabled"}} tabindex="4" id="modalidade-periodo" class="form-control" name="periodo">
                        @if(!$hasPackageFields)
                        <option value="">Selecione um período</option>
                        @endif
                        @foreach($periodos as $periodo)
                        <option {{(!empty($modalidade) && ($modalidade->periodo === $periodo["value"])) ? "selected" : ""}} value="{{$periodo["value"]}}">{{$periodo["text"]}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="modalidade-frequencia">Frequência *</label>
                    <select {!! (!empty($modalidade) && $modalidade->eModalidadeBase() ? "disabled": "") !!} {{$hasPackageFields ? "" : "disabled"}} tabindex="5" id="modalidade-frequencia" class="form-control" name="frequencia">
                        @if(!$hasPackageFields)
                        <option value="">Selecione uma frequência</option>
                        @endif
                        @foreach($frequencias as $frequencia)
                        <option {{(!empty($modalidade) && ($modalidade->frequencia === $frequencia["value"])) ? "selected" : ""}} value="{{$frequencia["value"]}}">{{$frequencia["text"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="modalidade-preco">Preço (por cão/hora) *</label>
                <input pattern="[0-9]" tabindex="6" id="modalidade-preco" class="form-control" type="number" min="1" name="precoPorCaoPorHora" value="{{!empty($modalidade) ? $modalidade->precoPorCaoPorHora : ""}}"/>
            </div>
            <div class="form-group">
                <label class="control-label" for="modalidade-coletivo">
                    Coletivo
                    <input {!! (!empty($modalidade) && $modalidade->eModalidadeBase() ? "disabled": "") !!} value="1" tabindex="7" id="modalidade-coletivo" class="form-control" type="checkbox" name="coletivo" {{!empty($modalidade) ? "checked" : ""}}/>
                </label>
            </div>   
        </fieldset>
        <button tabindex="8" type="submit" class="btn btn-default btn-lg pull-right">Salvar</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
(function () {
    var $form = $("#form-manter-modalidade");
    var $tipo = $form.find("select[name='tipo']");
    var $packageFields = $form.find("[data-role='package-related-fields']");
    var ignoreOnChecks = {};
    @if(!empty($ignoreOnChecks))
    ignoreOnChecks = $.parseJSON('{!! json_encode($ignoreOnChecks) !!}');
    @endif
    $form.on("blur", "", function (ev) {
        var $this = $(this);
        var nome = $this.val();
        if (validate.empty(nome)) {
            setInputStatus($this, "error");
            return;
        }
        $this.ajaxValidation(
            "{!!route('admin.modalidade.check.nome.get')!!}",
            "GET",
            {"nome": nome, "ignore": ignoreOnChecks.nome || null},
            "O nome informado já está sendo utilizado."
        );
    });
    $form.find("textarea[name='descricao']").validate("empty", null, "blur");
    $form.find("input[name='nome']").validate("empty", null, "blur");
    $tipo.validate("empty", null, "blur");
    $tipo.on("change", function(ev) {
        var tipo = $tipo.val();
        switch(tipo) {
            case "unitario":
                $packageFields.addClass("hidden");
                $packageFields.find("select,input,textarea").prop("disabled", true);
                break;
            case "pacote":
                $packageFields.removeClass("hidden");
                $packageFields.find("select,input,textarea").prop("disabled", false);
                break;
        }
    });
    
    $form.find("select[name='periodo']").validate("empty", null, "blur");
    $form.find("select[name='frequencia']").validate("empty", null, "blur");
    $form.find("input[name='precoPorCao']").validate("empty", null, "blur");
    $form.defaultAjaxSubmit("{!! route('admin.modalidade.listagem.get') !!}");
})();
</script>
@endsection