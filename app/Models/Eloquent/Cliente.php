<?php

namespace App\Models\Eloquent;

class Cliente extends Pessoa {

    protected $primaryKey = "idCliente";
    protected $table = 'cliente';

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Eloquent\Cancelamento", "pessoa", "cliente");
    }

    public function passeios() {
        return $this->hasManyThrough("\App\Models\Eloquent\Passeio", "\App\Models\Eloquent\Agendamento", "idCliente", "idAgendamento", "idPasseio");
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
