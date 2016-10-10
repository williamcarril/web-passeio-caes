@extends("walker.layouts.default")

@section("main")
<section>
    <h1>Login</h1>
    <form id="form-login" role="form" method="POST" action="{{route("walker.login.post")}}">
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
</section>
@endsection

