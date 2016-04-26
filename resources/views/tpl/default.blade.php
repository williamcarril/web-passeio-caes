<!DOCTYPE html>
<html>
    <head>
        <title>TCC</title>
        <link rel="stylesheet" href="/css/style.min.css">
    </head>
    <body>
        @section("header")
            @include("tpl._header")
        @show
        <div class="container">
            @section("main")
            @show
        </div>
        @section("footer")
            @include("tpl._footer")
        @show
        <scripts src="/js/scripts.min.js"></scripts>
        @section("scripts")
        @show
    </body>
</html>
