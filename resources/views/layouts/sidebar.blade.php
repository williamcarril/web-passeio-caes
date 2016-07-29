<ul class="sidebar-nav">
    <li class="sidebar-nav__logo">
        <a href="{{route("home")}}">
            <img src="{{asset("img/logo.png")}}" />
        </a>
    </li>
    <li class="sidebar-nav__user">
        @include("includes.user-area")
    </li>
    <li class="sidebar-nav__calendar">
        <span class="sidebar-nav__title">Calendário</span>
        @include("includes.calendar", ["type" => "small"])
    </li>
</ul>