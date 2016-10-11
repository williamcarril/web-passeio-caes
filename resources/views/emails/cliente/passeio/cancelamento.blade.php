@extends("layouts.email")
@section("main")
<p>
    Olá, {{$cliente->nome}}.<br/>
    O passeio marcado para o dia {{$passeio->dataFormatada}} entre os horários {{$passeio->inicioFormatado}} e {{$passeio->fimFormatado}} precisou ser cancelado.<br/>
    Para visualizar os detalhes deste passeio, clique no link a seguir:<br/>
    <a target="_blank" href="{{route("cliente.passeio.detalhes.get", ["id" => $passeio->idAgendamento])}}">{{route("cliente.agendamento.detalhes.get", ["id" => $passeio->idAgendamento])}}</a><br/>
    Atenciosamente,<br/>
    Anamá
</p>
@endsection