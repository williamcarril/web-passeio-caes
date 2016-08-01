<!DOCTYPE html>
<html>
    <head>
        <title>@yield("title", "Passeio de Cães")</title>
        <link rel="stylesheet" href="{{asset("/css/styles.min.css")}}">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @if(!\App::environment("production"))
        <meta name="robots" content="noindex, nofollow" />
        @else
        <meta name="robots" content="index, follow" />
        @endif
        
        <meta content="Passeio de Cães" name="application-name"/>
        <meta content="@yield('keywords', '')" name="keywords" />
        <meta content="@yield('description', '')" name="description"/>
    </head>
    <body>
        @section("header")
        @include("layouts.header")
        @show
        <div id="wrapper">
            <div id="sidebar-wrapper">
                @section("sidebar")
                @include("layouts.sidebar")
                @show
            </div>
            <div id="page-content-wrapper">
                <div class="container">
                    @section("main")
                    @show
                </div>
            </div>
        </div>
        @section("footer")
        @include("layouts.footer")
        @show
        <script src="{{asset("/js/scripts.min.js")}}"></script>
        @section("scripts")
        @show
    </body>
</html>
