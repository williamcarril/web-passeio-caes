@extends("layouts.default")

@section("main")
<form method="POST" action="/api/multimidia" enctype="multipart/form-data">
    <input type="text" name="nome">
    <input type="text" name="descricao">
    <input type="file" name="arquivo">
    <input type="submit">
</form>
@endsection