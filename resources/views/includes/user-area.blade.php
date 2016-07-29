@if(empty($customer))
<form method="POST" action="{{route("login.post")}}" role="form">
    {!! csrf_field() !!}
    <div class="form-group">
        <label class="sr-only" for="email">E-mail</label>
        <div class="input-group">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-user"></span>
            </span>
            <input type="email" class="form-control" placeholder="E-mail">
        </div>
    </div>
    <div class="form-group">
        <label class="sr-only" for="pass">Senha</label>
        <div class="input-group">
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-lock"></span>
            </span>
            <input type="password" class="form-control" placeholder="Senha">
        </div>
    </div>
    <button type="submit" class="btn btn-default">Entrar</button>
</form>
@else
<p>Olá, {{$customer->nome}}</p>
@endif