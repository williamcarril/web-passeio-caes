<?php

namespace App\Models\Eloquent;

class Cliente extends Pessoa {

    protected $primaryKey = "idCliente";
    protected $table = 'cliente';

    public function cancelamentos() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento",
        "idPessoa",
        "idCliente"
        )->where("tipoPessoa", "cliente");
    }

    public function passeios() {
        $idsAgendamentos = $this->agendamentos->map(function($agendamento) {
                    return $agendamento->idAgendamento;
                })->toArray();
        return Passeio::whereHas("agendamentos", function($q) use ($idsAgendamentos) {
                    $q->whereIn("agendamento.idAgendamento", $idsAgendamentos);
                });
    }

    public function passeiosConfirmados() {
        $idsAgendamentos = $this->agendamentos->map(function($agendamento) {
                    return $agendamento->idAgendamento;
                })->toArray();

        $idsCaes = $this->caes->map(function($cao) {
                    return $cao->idCao;
                })->toArray();
        return Passeio::whereHas("agendamentos", function($q) use ($idsAgendamentos) {
                    $q->whereIn("agendamento.idAgendamento", $idsAgendamentos);
                    $q->where("status", Enums\AgendamentoStatus::FEITO);
                })->whereHas("caes", function($q) use ($idsCaes) {
                    $q->whereIn("cao.idCao", $idsCaes);
                });
    }

    public function caes() {
        return $this->hasMany("\App\Models\Eloquent\Cao", "idCliente", "idCliente");
    }

    public function horariosDeInteresse() {
        return $this->hasMany("\App\Models\Eloquent\Horario", "idCliente", "idCliente");
    }

    public function agendamentos() {
        return $this->hasMany("\App\Models\Eloquent\Agendamento", "idCliente", "idCliente");
    }

    public function getAuthIdentifierName() {
        return "idCliente";
    }

    protected function overrideNormalRules($rules) {
        $rules["email"][] = "unique:cliente,email,{$this->idCliente},idCliente";
        $rules["cpf"][] = "unique:cliente,cpf,{$this->idCliente},idCliente";
        return $rules;
    }

}
