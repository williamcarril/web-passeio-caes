<?php
$id = !empty($id) ? $id : uniqid("map");
$zoom = !empty($zoom) ? $zoom : 16;
$lat = !empty($lat) ? $lat : -23.63793;
$lng = !empty($lng) ? $lng : -46.57817;
$searchBox = isset($searchBox) ? $searchBox : true;
?>
@if($searchBox)
<input class="map__search-input" id="search-{{$id}}" type="text" placeholder="Search Box">
@endif
<div class="map" id="{{$id}}" data-role="map"></div>
@section("scripts")
@parent
<script type="text/javascript">
    (function () {
        $(window).load(function () {
            var map = new google.maps.Map(document.getElementById("{!! $id !!}"), {
                "center": {
                    "lat": parseFloat("{!! $lat !!}"), 
                    "lng": parseFloat("{!! $lng !!}")
                },
                "zoom": parseInt("{!! $zoom !!}")
            });
            
            if(!globals.maps) {
                globals.maps = [];
            }
            globals.maps["{!! $id !!}"] = map;
            
            @if($searchBox)
            var input = document.getElementById("search-{!! $id !!}");
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            @endif
            
            @if(isset($callback))
            window["{!! $callback !!}"]();
            @endif
        });
    })();
</script>
@endsection
