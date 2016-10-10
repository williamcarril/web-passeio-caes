<?php
$hasLinkToHome = isset($hasLinkToHome) ? $hasLinkToHome : true;
$link = isset($link) ? $link : null;
$linkMessage = isset($linkMessage) ? $linkMessage : "Retorne";
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
                background-color: #0D2034;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            a {
                color: #999;
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
        <meta content="{{config("app.name")}}" name="application-name"/>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">@yield("title")</div>
                @yield("message")
                <br/>
                <br/>
                @if(!is_null($link))
                <p>{{$linkMessage}} clicando <a href="{{$link}}">aqui</a>.</p>
                <a href="{{$link}}"><img src="{{asset("img/medium-logo-white.png")}}" /></a>
                @else
                @if($hasLinkToHome)
                <p>Retorne para a HOME clicando <a href="{{route("walker.home")}}">aqui</a>.</p>
                <a href="{{route("admin.home")}}"><img src="{{asset("img/medium-logo-white.png")}}" /></a>
                @else
                <img src="{{asset("img/logo-white.png")}}" />
                @endif
                @endif
            </div>
        </div>
    </body>
</html>
