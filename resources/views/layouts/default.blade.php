<?php
$hasMap = isset($hasMap) ? $hasMap : false;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>@yield("title", env("APP_NAME"))</title>
        <link rel="stylesheet" href="{{asset("/css/styles.min.css")}}">
        <meta name="csrf-token" content="{{csrf_token()}}">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @if(!\App::environment("production"))
        <meta name="robots" content="noindex, nofollow" />
        @else
        <meta name="robots" content="index, follow" />
        @endif

        <meta content="{{env("APP_NAME")}}" name="application-name"/>
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
        @if($hasMap)
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{config("services.google.maps.key")}}">
        </script>
        @endif
        @section("scripts")
        @show
    </body>
</html>
