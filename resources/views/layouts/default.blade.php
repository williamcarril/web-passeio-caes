<!DOCTYPE html>
<html>
    <head>
        <title>Passeio de Cães</title>
        <link rel="stylesheet" href="{{asset("/css/style.min.css")}}">
        <meta name="csrf-token" content="{{csrf_token()}}">
    </head>
    <body>
        @section("header")
            @include("layouts._header")
        @show
        <div class="container">
            @section("main")
            @show
        </div>
        @section("footer")
            @include("layouts._footer")
        @show
        <script src="{{asset("/js/scripts.min.js")}}"></script>
        @section("scripts")
        @show
    </body>
</html>
