<?php 
$dataTemplate = isset($dataTemplate) ? $dataTemplate : "cliente-tooltip-$cliente->idCliente";
?>
<div data-template="{{$dataTemplate}}">
    <p>
        Cliente: {{$cliente->nome}}<br/>
        @if($passeio->getCaesConfirmadosDoCliente($cliente)->count() > 1)
        Cães: {{$passeio->getCaesConfirmadosDoClienteFormatados($cliente)}}
        @else
        Cão: {{$passeio->getCaesConfirmadosDoClienteFormatados($cliente)}}
        @endif
    </p>
</div>