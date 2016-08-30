<section>
    @if(empty($customer))
    <form method="POST" action="{{route("cliente.auth.login")}}" role="form">
        {!! csrf_field() !!}
        <div class="form-group">
            <label class="sr-only" for="email">E-mail</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-user"></i>
                </span>
                <input name="email" type="email" class="form-control" placeholder="E-mail">
            </div>
        </div>
        <div class="form-group">
            <label class="sr-only" for="pass">Senha</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-lock"></i>
                </span>
                <input name="senha" type="password" class="form-control" placeholder="Senha">
            </div>
        </div>
        <button type="submit" class="btn btn-default">Entrar</button>
    </form>
    <span>Não tem cadastro? Cadastre-se <a href="{{route("cliente.cadastro.get")}}">aqui</a>.</span>
    @else
    Olá, {{$customer->nome}}.
    <section>
        <a href="{{route("cliente.cadastro.get")}}">
            <i class="glyphicon glyphicon-pencil"></i>
            Alterar cadastro
        </a>
        <br/>
        <a href="{{route("cliente.caes.get")}}">
            <i class="flaticon-dog"></i>
            Manter cachorros
        </a>
        <br/>
        <a href="{{route("cliente.auth.logout")}}">
            <i class="glyphicon glyphicon-off"></i>
            Sair
        </a>
        <br/>
    </section>
    @endif
</section>