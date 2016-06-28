@extends('layouts.default')

@section("main")
<form action="{{route("test.post")}}" method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="text" name="text">
    {!! csrf_field() !!}
    <input type="submit">
</form>
@endsection