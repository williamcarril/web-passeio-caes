<section>
    <center>
        <img src="{{$passeador->thumbnail}}" alt="Foto"/>
    </center>
    Olá, {{$passeador->nome}}.
    <section>
        <a href="{{route("walker.passeio.confirmado.listagem.get")}}">
            <i class="flaticon-walking-dog"></i>
            Visualizar passeios
        </a>
        <br/>
        <a href="{{route("walker.logout.get")}}">
            <i class="glyphicon glyphicon-off"></i>
            Sair
        </a>
        <br/>
    </section>
</section>