<?php
$hasMap = isset($hasMap) ? $hasMap : false;
?>
<!DOCTYPE html>
<html lang="{{config("app.locale")}}">
    <head>
        <title>@yield("title", "Administrativo | " . config("app.name"))</title>
        <link rel="stylesheet" href="{{asset("/css/adm_styles.min.css")}}" />
        <link rel="shortcut icon" href="{{asset("/img/logo-black.ico")}}" >

        <meta name="csrf-token" content="{{csrf_token()}}">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta name="robots" content="noindex, nofollow" />

        <meta content="{{config("app.name")}}" name="application-name"/>
        <meta content="@yield('keywords', '')" name="keywords" />
        <meta content="@yield('description', '')" name="description"/>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                @section("header")
                @include("admin.layouts.header")
                @show
            </header>
            <div id="content">
                <aside id="sidebar">
                    @section("sidebar")
                    @include("admin.layouts.sidebar")
                    @show
                </aside>
                <main id="main">
                    @include("layouts.alerts")
                    <div class="container">
                        @section("main")
                        @show
                    </div>
                </main>
                <footer id="footer">
                    @section("footer")
                    @include("admin.layouts.footer")
                    @show
                </footer>
            </div>
        </div>
        @include("layouts.modals.confirm")
        @include("layouts.modals.credits")
        <div id="html-templates">
            @include("includes.alert", ["type" => "error", "message" => "!{message}", "name" => "error-alert"])
            @include("includes.alert", ["type" => "info", "message" => "!{message}", "name" => "info-alert"])
            @include("includes.alert", ["type" => "success", "message" => "!{message}", "name" => "success-alert"])
            @include("includes.alert", ["type" => "warning", "message" => "!{message}", "name" => "warning-alert"])
            @section("templates")
            @show
        </div>
        <script src="{{asset("/js/adm_scripts.min.js")}}"></script>
        @if($hasMap)
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{config("services.google.maps.key")}}&libraries=places">
        </script>
        @endif
        @section("scripts")
        @show
    </body>
</html>
