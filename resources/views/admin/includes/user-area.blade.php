<section>
    <center>
        <img src="{{$administrator->thumbnail}}" alt="Foto"/>
    </center>
    Olá, {{$administrator->nome}}.
    <section>
        <a href="{{route("admin.funcionario.alterar.get")}}">
            <i class="glyphicon glyphicon-pencil"></i>
            Alterar cadastro
        </a>
        <br/>
        <a href="{{route("admin.logout.get")}}">
            <i class="glyphicon glyphicon-off"></i>
            Sair
        </a>
        <br/>
    </section>
</section>