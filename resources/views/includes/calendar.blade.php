<?php
$type = isset($type) ? $type : null;
switch ($type) {
    default:
        $classes = "";
}
$id = isset($id) ? $id : uniqid("calendar");
?>
<div id="{{$id}}" class="responsive-calendar {{$classes}}">
    <div class="controls">
        <a class="pull-left" data-go="prev">
            <div class="btn">
                <i class="glyphicon glyphicon-chevron-left"></i>
            </div>
        </a>
        <h4>
            <span data-head-month></span>
            <span data-head-year></span> 
        </h4>
        <a class="pull-right" data-go="next">
            <div class="btn">
                <i class="glyphicon glyphicon-chevron-right"></i>
            </div>
        </a>
    </div><hr/>
    <div class="day-headers">
        <div class="day header">Seg</div>
        <div class="day header">Ter</div>
        <div class="day header">Qua</div>
        <div class="day header">Qui</div>
        <div class="day header">Sex</div>
        <div class="day header">Sáb</div>
        <div class="day header">Dom</div>
    </div>
    <div class="days" data-group="days">
        <!-- the place where days will be generated -->
    </div>
</div>
@section("scripts")
@parent
@if(!empty($events))
<script type="text/javascript">
    (function () {
        var events = $.parseJSON('{!!json_encode($events)!!}');
        $("#{!!$id!!}").responsiveCalendar("edit", events);
    })();
</script>
@endif
@endsection