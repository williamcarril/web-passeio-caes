<?php
$askToNotRespond = isset($askToNotRespond) ? $askToNotRespond : true;
$removeLogo = isset($removeLogo) ? $removeLogo : false;
?>
<html lang="{{config("app.locale")}}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield("title", config("app.name"))</title>
        <style>
            a {
                color: #999;

                &:hover {
                    color: #666;
                }
            }
        </style>
    </head>
    <body style="font-family: 'Oswald',sans-serif;">
        <table style="
               border-spacing: 10px;
               background-color: @yield("baseColor", "#367A38"); 
               border:1px solid black;
               text-align: left;
               ">
            @if(!$removeLogo)
            <tr>
                <td style="
                    padding: 5px;
                    background-color: #FFFFFF; 
                    border: 1px solid black;
                    text-align: center;
                    ">
                    <img src='@yield("logo", asset("img/logo.png"))' alt='{{config("app.name")}}' />
                </td>
            </tr>
            @endif
            <tr>
                <td style="
                    padding: 5px;
                    background-color: #FFFFFF; 
                    border: 1px solid black; 
                    ">
                    @yield("main")
                    @if($askToNotRespond)
                    <p style="text-align: center;">
                        <b>*** Esse é um e-mail automático. Não é necessário respondê-lo ***</b>
                    </p>
                    @endif
                </td>
            </tr>
        </table>
    </body>
</html>