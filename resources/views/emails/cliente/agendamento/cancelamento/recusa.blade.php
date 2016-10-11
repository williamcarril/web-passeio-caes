@extends("layouts.email")
@section("main")
<p>
    Olá, {{$cliente->nome}}.<br/>
    O agendamento que você realizou no dia {{$agendamento->dataFormatada}} não pôde ser aceito.<br/>
    Para visualizar os detalhes deste agendamento, clique no link a seguir:<br/>
    <a target="_blank" href="{{route("cliente.agendamento.detalhes.get", ["id" => $agendamento->idAgendamento])}}">{{route("cliente.agendamento.detalhes.get", ["id" => $agendamento->idAgendamento])}}</a><br/>
    Atenciosamente,<br/>
    Anamá
</p>
@endsection