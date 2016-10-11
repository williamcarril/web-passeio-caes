<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            @if(!empty($administrator))
            <button class="navbar-toggle" type="button" data-action="sidebar-toggler">
                <span class="sr-only">Toggle sidebar</span>
                <i class="glyphicon glyphicon-menu-left"></i>
            </button>
            <button class="navbar-toggle" type="button" aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="glyphicon glyphicon-menu-hamburger"></i>
            </button>
            @endif
            <a href="{{route("admin.home")}}" class="navbar-brand">
                <img src="{{asset("img/logo-black.png")}}" alt="Anamá"/>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar" aria-expanded="false">
            @if(!empty($administrator))
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{route("admin.agendamento.listagem.get")}}"
                       {!! (!empty($agendamentosPendentes) ? "data-toggle='tooltip' title='Agendamentos pendentes'" : "") !!}>
                        Agendamentos
                        @if(!empty($agendamentosPendentes))
                        <span class="badge">{{$agendamentosPendentes}}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{route("admin.passeio.marcados.listagem.get")}}" 
                       {!! (!empty($passeiosPendentesSemPasseadores) ? "data-toggle='tooltip' title='Passeios pendentes sem passeadores alocados'" : "") !!}>
                        Passeios
                        @if(!empty($passeiosPendentesSemPasseadores))
                        <span class="badge">{{$passeiosPendentesSemPasseadores}}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{route("admin.funcionario.passeador.listagem.get")}}">
                        Passeadores
                    </a>
                </li>
                <li>
                    <a href="{{route("admin.local.listagem.get")}}">Locais</a>
                </li>
                <li>
                    <a href="{{route("admin.modalidade.listagem.get")}}">Modalidades</a>
                </li>
                <li>
                    <a href="{{route("admin.cancelamento.listagem.get")}}"
                       {!! (!empty($cancelamentosNaoVerificados) ? "data-toggle='tooltip' title='Cancelamentos não verificados'" : "") !!}>
                        Cancelamentos
                        @if(!empty($cancelamentosNaoVerificados))
                        <span class="badge">{{$cancelamentosNaoVerificados}}</span>
                        @endif
                    </a>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>