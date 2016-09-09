@extends("layouts.default", ["hasMap" => true])

@section("title") Cadastro | {{config("app.name")}} @endsection

@section("main")
<section>
    <h1>Cadastro - Cliente</h1>
    <form id="form-cadastro-cliente" role="form" method="POST" action="{{route("cliente.cadastro.post")}}">
        {!! csrf_field() !!}
        <fieldset>
            <legend>Informações gerais</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-nome">Nome *</label>
                <input tabindex="1" value="{{!empty($customer->nome) ? $customer->nome : ""}}" required name="nome" id="cliente-nome" type="text" class="form-control" placeholder="Informe seu nome completo">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-cpf">CPF *</label>
                <input tabindex="2" value="{{!empty($customer->cpf) ? $customer->cpf : ""}}" required name="cpf" data-inputmask="'mask': '999.999.999-99'" type="text" id="cliente-cpf" class="form-control" placeholder="Informe seu CPF">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-telefone">Telefone *</label>
                <input tabindex="3" value="{{!empty($customer->telefone) ? $customer->telefone : ""}}" required name="telefone" data-inputmask="'mask': '(99) 9999[9]-9999', 'greedy': false" id="cliente-telefone" type="tel" class="form-control" placeholder="Informe seu telefone">
            </div>
        </fieldset>
        <fieldset>
            <legend>Informações de acesso</legend>
            <div class="form-group">
                <label class="control-label" for="cliente-email">E-mail *</label>
                <input tabindex="4" value="{{!empty($customer->email) ? $customer->email : ""}}" required name="email" id="cliente-email" type="email" class="form-control" placeholder="Informe seu e-mail">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha">Senha *</label>
                <input tabindex="5" {{!empty($customer) ? "" : "required"}} name="senha" id="cliente-senha" type="password" class="form-control" placeholder="Informe sua senha">
            </div>
            <div class="form-group">
                <label class="control-label" for="cliente-senha2">Confirme sua senha *</label>
                <input tabindex="6" {{!empty($customer) ? "" : "required"}} name="senha2" id="cliente-senha2" type="password" class="form-control" placeholder="Confirme sua senha">
            </div>
        </fieldset>
        <?php 
            $mapFieldsetData = [
                "alertOnPin" => true,
                "idPrefix" => "cliente",
                "possessivePlaceholders" => true,
                "values" => !empty($customer) ? $customer : null,
                "customMarker" => asset("img/markers/user.png"),
                "tabIndex" => 7
            ];
        ?>
        @include("includes.form.map-fieldset", $mapFieldsetData)
        <button tabindex="12" type="submit" class="btn btn-default btn-lg pull-right">{{!empty($customer) ? "Salvar" : "Cadastrar"}}</button>
    </form>
</section>
@endsection

@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        var $form = $("#form-cadastro-cliente");

        $form.find("input[name='nome']").validate("empty", null, "blur");

        $form.find("input[name='telefone']").validate("phone", null, "blur");

        $form.find("input[name='cpf']").on("blur", function () {
            var $this = $(this);
            var cpf = $this.val();
            if (!validate.cpf(cpf)) {
                setInputStatus($this, "error");
                return;
            }
            $this.ajaxValidation(
                "{!! route('cliente.cadastro.check.cpf.get') !!}",
                "GET",
                {"cpf": cpf},
                "O CPF informado já está sendo utilizado."
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
                "{!! route('cliente.cadastro.check.email.get') !!}",
                "GET",
                {"email": email},
                "O e-mail informado já está sendo utilizado."
            );
        });

        @if(!empty($customer))
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
            $senha.validate("nonEmptyEquals", $senha2);
        });
        @endif
        $form.defaultAjaxSubmit("{!! route('home') !!}");
    })();
</script>
@endsection