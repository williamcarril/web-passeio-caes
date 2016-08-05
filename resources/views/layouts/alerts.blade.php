<div id="alerts">
    @if(!empty($errors->all()))
        @foreach($errors as $error)
        @include("includes.alert", ["type" => "error", "message" => $error])
        @endforeach
    @endif
</div>