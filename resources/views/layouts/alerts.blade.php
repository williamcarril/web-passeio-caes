<div id="alerts">
    @foreach($errors->all() as $error)
    @include("includes.alert", ["type" => "error", "message" => $error])
    @endforeach
</div>