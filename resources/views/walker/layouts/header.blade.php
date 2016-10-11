<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            @if(!empty($passeador))
            <button class="navbar-toggle" type="button" data-action="sidebar-toggler">
                <span class="sr-only">Toggle sidebar</span>
                <i class="glyphicon glyphicon-menu-left"></i>
            </button>
            <button class="navbar-toggle" type="button" aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="glyphicon glyphicon-menu-hamburger"></i>
            </button>
            @endif
            <a href="{{route("walker.home")}}" class="navbar-brand">
                <img src="{{asset("img/logo-blue.png")}}" alt="Anamá"/>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar" aria-expanded="false">
            @if(!empty($passeador))
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{route("walker.passeio.confirmado.listagem.get")}}"
                       {!! (!empty($passeiosPendentes) ? "data-toggle='tooltip' title='Passeios pendentes'" : "") !!}>
                        Passeios
                        @if(!empty($passeiosPendentes))
                        <span class="badge">{{$passeiosPendentes}}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{route("walker.local.listagem.get")}}">Locais</a>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>