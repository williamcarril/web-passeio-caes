@extends("layouts.email")
@section("main")
<p>
    Olá, {{$cliente->nome}}.<br/>
    O agendamento que você realizou no dia {{$agendamento->dataFormatada}} não pôde ser aceito, porém há uma sugestão de reagendamento pendente para você.<br/>
    Para visualizá-la, clique no link a seguir:<br/>
    <a target="_blank" href="{{route("cliente.agendamento.detalhes.get", ["id" => $reagendamento->idAgendamento])}}">{{route("cliente.agendamento.detalhes.get", ["id" => $reagendamento->idAgendamento])}}</a><br/>
    Atenciosamente,<br/>
    Anamá
</p>
@endsection