<?php
$title = isset($title) ? $title : "Funcionário";
?>    

@extends("admin.layouts.default", ["hasMap" => true])

@section("title") {{$title}} | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>{{$title}}</h1>
    <form id="form-manter-funcionario" role="form" method="POST" action="{{route("admin.funcionario.salvar.post")}}" enctype="multpart/form-data">
        {!! csrf_field() !!}
        @if(!empty($funcionario))
        <input type="hidden" name="id" value="{{$funcionario->idFuncionario}}"/>
        @endif
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="funcionario-foto">Foto *</label>
                @if(!empty($funcionario))
                @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem", "image" => $funcionario->thumbnail, "imageDescription" => $funcionario->nome])
                @else
                @include("includes.image-uploader", ["placeholder" => false, "icon" => false, "name" => "imagem", "required" => true, "image" => asset("img/picture-black.png")])
                @endif
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-nome">Nome *</label>
                <input tabindex="1" value="{{!empty($funcionario->nome) ? $funcionario->nome : ""}}" required name="nome" id="funcionario-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-cpf">CPF *</label>
                <input tabindex='2' value="{{!empty($funcionario->cpf) ? $funcionario->cpf : ""}}" required name="cpf" data-inputmask="'mask': '999.999.999-99'" type="text" id="funcionario-cpf" class="form-control" placeholder="Informe seu CPF">
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-rg">RG *</label>
                <input tabindex='3' value="{{!empty($funcionario->rg) ? $funcionario->rg : ""}}" required name="rg" type="number" id="funcionario-rg" class="form-control -no-spin" placeholder="Informe seu RG">
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-telefone">Telefone *</label>
                <input tabindex='4' value="{{!empty($funcionario->telefone) ? $funcionario->telefone : ""}}" required name="telefone" data-inputmask="'mask': '(99) 9999[9]-9999', 'greedy': false" id="funcionario-telefone" type="tel" class="form-control" placeholder="Informe seu telefone">
            </div>
        </fieldset>
        @if(!empty($portes))
        <fieldset>
            <legend>Informações relativas à execução de passeios</legend>
            <p>Limite de cães por porte</p>
            @foreach($portes as $porte)
            <?php 
            $limite = $funcionario->limiteDeCaes->where("porte", $porte["value"])->first();
            ?>
            <div class="form-group">
                <label class="control-label" for="funcionario-{{$porte["value"]}}">{{$porte["text"]}}</label>
                <input class="form-control" type="number" value="{{!empty($limite) ? $limite->limite : ""}}" name="limite[{{$porte["value"]}}]">
            </div>
            @endforeach
        </fieldset>
        @endif
        <fieldset>
            <legend>Informações de acesso</legend>
            <div class="form-group">
                <label class="control-label" for="funcionario-email">E-mail *</label>
                <input tabindex='5' value="{{!empty($funcionario->email) ? $funcionario->email : ""}}" required name="email" id="funcionario-email" type="email" class="form-control" placeholder="Informe seu e-mail">
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-senha">Senha *</label>
                <input tabindex="6" {{!empty($funcionario) ? "" : "required"}} name="senha" id="funcionario-senha" type="password" class="form-control" placeholder="Informe sua senha">
            </div>
            <div class="form-group">
                <label class="control-label" for="funcionario-senha2">Confirme sua senha *</label>
                <input tabindex='7' {{!empty($funcionario) ? "" : "required"}}  name="senha2" id="funcionario-senha2" type="password" class="form-control" placeholder="Confirme sua senha">
            </div>
        </fieldset>
        <?php 
        $mapFieldsetData = [
            "idPrefix" => "funcionario",
            "values" => !empty($funcionario) ? $funcionario : null,
            "tabIndex" => 8
        ];
        ?>
        @include("includes.form.map-fieldset", $mapFieldsetData)
        <button tabindex='13' type="submit" class="btn btn-default btn-lg pull-right">Salvar</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $form = $("#form-manter-funcionario");
        var ignoreOnChecks = {};
        @if(!empty($ignoreOnChecks))
            var ignoreOnChecks = $.parseJSON('{!! json_encode($ignoreOnChecks) !!}');
        @endif
        $form.find("input[name='nome']").validate("empty", null, "blur");

        $form.find("input[name='telefone']").validate("phone", null, "blur");
        
        $form.find("input[name='cpf']").on("blur", function() {
            var $this = $(this);
            var cpf = $this.val();
            if(!validate.cpf(cpf)) {
                setInputStatus($this, "error");
                return;
            }
            $this.ajaxValidation(
                "{!! route('admin.funcionario.check.cpf.get') !!}",
                "GET",
                {"cpf": cpf, "ignore": ignoreOnChecks.cpf || null},
                "O CPF informado já está sendo utilizado."
            );
        });
        
        $form.find("input[name='rg']").on("blur", function () {
            var $this = $(this);
            var rg = $this.val();
            if (validate.empty(rg)) {
                setInputStatus($this, "error");
                return;
            }
            $this.ajaxValidation(
                "{!! route('admin.funcionario.check.rg.get') !!}",
                "GET",
                {"rg": rg, "ignore": ignoreOnChecks.rg || null},
                "O RG informado já está sendo utilizado."
            );
        });

        $form.find("input[name='email']").on("blur", function () {
            var $this = $(this);
            var email = $this.val();
            if (!validate.email(email)) {
                setInputStatus($this, "error");
                return;
            }
            $this.ajaxValidation(
                "{!! route('admin.funcionario.check.rg.get') !!}",
                "GET",
                {"email": email, "ignore": ignoreOnChecks.email || null},
                "O RG informado já está sendo utilizado."
            );
        });

        @if(!empty($funcionario))
        $form.find("input[name='senha'],input[name='senha2']").on("blur", function () {
            var $senha = $form.find("input[name='senha']");
            var $senha2 = $form.find("input[name='senha2']");
            if (validate.empty($senha.val())) {
                return;
            }
            $senha.validate("equals", $senha2);
        });
        @else
        $form.find("input[name='senha'],input[name='senha2']").on("blur", function () {
            var $senha = $form.find("input[name='senha']");
            var $senha2 = $form.find("input[name='senha2']");
            $senha.validate("equals", $senha2);
        });
        @endif

        $form.defaultAjaxSubmit("{!! Request::url() !!}");
    })();
</script>
@endsection