<!DOCTYPE html>
<html>
    <head>
        <title>Passeio de Cães</title>
        <link rel="stylesheet" href="/css/style.min.css">
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
        <scripts src="/js/scripts.min.js"></scripts>
        @section("scripts")
        @show
    </body>
</html>
