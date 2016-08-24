<ul class="sidebar-nav">
    <li class="logo">
        <a href="{{route("home")}}">
            <img src="{{asset("img/logo.png")}}" alt="Anamá"/>
        </a>
    </li>
    <li class="user">
        @include("includes.customer-area")
    </li>
    {{--    
    <li class="calendar">
        <span class="title">Calendário</span>
        @include("includes.calendar", ["type" => "sidebar"])
    </li>
    --}}
</ul>