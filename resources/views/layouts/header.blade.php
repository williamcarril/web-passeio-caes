<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-action="sidebar-toggler">
                <span class="sr-only">Toggle sidebar</span>
                <i class="glyphicon glyphicon-menu-left"></i>
            </button>
            <button class="navbar-toggle" type="button" aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse">
                <span class="sr-only">Toggle navigation</span>
                <i class="glyphicon glyphicon-menu-hamburger"></i>
            </button>
            <a href="{{route("home")}}" class="navbar-brand">
                <img src="{{asset("img/logo.png")}}" alt="Anamá"/>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar" aria-expanded="false">
            <ul class="nav navbar-nav">
                <li><a href="{{route("local.listagem.get")}}">Locais</a></li>
                @if(!empty($customer))
                <li><a href="{{route("passeio.agenda.get")}}">Agendamento</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>