<section>
    Olá, {{$administrator->nome}}.
    <section>
        <a href="{{route("admin.funcionario.alterar.get", ["id" => $administrator->idFuncionario])}}">
            <i class="glyphicon glyphicon-pencil"></i>
            Alterar cadastro
        </a>
        <br/>
        <br/>
    </section>
</section>