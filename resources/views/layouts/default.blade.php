<?php
$hasMap = isset($hasMap) ? $hasMap : false;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>@yield("title", env("APP_NAME"))</title>
        <link rel="stylesheet" href="{{asset("/css/styles.min.css")}}" />
        <meta name="csrf-token" content="{{csrf_token()}}">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
            <aside id="sidebar-wrapper">
                @section("sidebar")
                @include("layouts.sidebar")
                @show
            </aside>
            <div id="page-content-wrapper">
                @include("layouts.alerts")
                <div class="container">
                    @section("main")
                    @show
                </div>
            </div>
        </div>
        <div id="htmlTemplates">
            @include("includes.alert", ["type" => "error", "message" => "!{message}", "name" => "error-alert"])
            @include("includes.alert", ["type" => "info", "message" => "!{message}", "name" => "info-alert"])
            @include("includes.alert", ["type" => "success", "message" => "!{message}", "name" => "success-alert"])
            @include("includes.alert", ["type" => "warning", "message" => "!{message}", "name" => "warning-alert"])
        </div>
        @section("footer")
        @include("layouts.footer")
        @show
        <script src="{{asset("/js/scripts.min.js")}}"></script>
        @if($hasMap)
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{config("services.google.maps.key")}}&libraries=places">
        </script>
        @endif
        @section("scripts")
        @show
    </body>
</html>
