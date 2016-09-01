<?php
$hasLinkToHome = isset($hasLinkToHome) ? $hasLinkToHome : true;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>@yield("title")</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #FFF;
                background-color: #367A38;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            a {
                color: #BEEE9E;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
                font-size: 24px;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="Passeio de Cães" name="application-name"/>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">@yield("title")</div>
                @yield("message")
                <br/>
                <br/>
                @if($hasLinkToHome)
                <p>Retorne para a HOME do site clicando <a href="{{route("home")}}">aqui.</a></p>
                <a href="{{route("home")}}"><img src="{{asset("img/logo.png")}}" /></a>
                @else
                <img src="{{asset("img/logo.png")}}" />
                @endif
            </div>
        </div>
    </body>
</html>
