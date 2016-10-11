<?php
$thumbnail = isset($thumbnail) ? $thumbnail : asset("img/local.png");
$agendable = isset($agendable) ? $agendable : false;
$agendableLink = isset($agendableLink) ? $agendableLink : "#";
$link = isset($link) ? $link : "#";
?>
<article class="place-box {{$agendable ? "-agendable" : ""}}">
    <header class="header">
        <a href="{{$link}}">
            <img class="image" src="{{$thumbnail}}" alt="{{$name}}" />
            <h2 class="title">{{$name}}</h2>
        </a>
    </header>
    <section class="content">
        <a class="address" href="http://maps.google.com/?q={{"$lat,$lng"}}" target="_blank">
            <i class="glyphicon glyphicon-map-marker"></i>
            {{\str_limit($address, 35)}}
        </a>
        <p class="description">{{\str_limit($description, 105)}}</p>
    </section>
    <footer class="footer">
        <div class="button-group">
            @if($agendable)
            <a class="btn btn-success" href="{{$agendableLink}}">
                <i class="flaticon-calendar"></i>
                Agendar passeio
            </a>
            @endif
            <a class="btn btn-default" href="{{$link}}">
                <i class="glyphicon glyphicon-search"></i>
                Detalhes
            </a>
        </div>
    </footer>
</article>
