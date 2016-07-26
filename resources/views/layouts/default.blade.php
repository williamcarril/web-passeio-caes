<!DOCTYPE html>
<html>
    <head>
        <title>Passeio de Cães</title>
        <link rel="stylesheet" href="{{asset("/css/styles.min.css")}}">
        <meta name="csrf-token" content="{{csrf_token()}}">
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
                <div class="container-fluid">
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
