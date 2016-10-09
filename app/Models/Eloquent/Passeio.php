<?php

namespace App\Models\Eloquent;

use \App\Models\Eloquent\Enums\Porte;
use \App\Models\Eloquent\Enums\PasseioStatus as Status;
use \App\Models\Eloquent\Enums\AgendamentoStatus;

class Passeio extends \WGPC\Eloquent\Model {

    protected $table = "passeio";
    protected $primaryKey = "idPasseio";
    protected $fillable = [
        "idLocal",
        "idPasseador",
        "idPasseioOriginal",
        "inicio",
        "fim",
        "data",
        "status",
        "coletivo",
        "porte"
    ];
    protected static $rules = [
        "idLocal" => ["required", "exists:local,idLocal", "integer"],
        "idPasseador" => ["exists:funcionario,idFuncionario,tipo,passeador", "integer"],
        "idPasseioOriginal" => ["exists:passeio,idPasseio", "integer"],
        "coletivo" => ["boolean", "required"],
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "data" => ["required", "date"],
        "status" => ["required", "string"],
        "porte" => ["string"],
    ];
    protected $casts = [
        "coletivo" => "boolean",
    ];
    protected $attributes = [
        "coletivo" => false,
        "status" => Status::EM_ANALISE
    ];

    public static function boot() {
        parent::boot();
        static::$rules["porte"][] = "in:" . implode(",", Porte::getConstants());
        static::$rules["status"][] = "in:" . implode(",", Status::getConstants());
    }

    public function agendamentos() {
        return $this->belongsToMany("\App\Models\Eloquent\Agendamento", "a_agendamento_passeio", "idPasseio", "idAgendamento");
    }

    public function passeioOriginal() {
        return $this->belongsTo("\App\Models\Eloquent\Passeio", "idPasseioOriginal", "idPasseio");
    }

    public function passeioRemarcado() {
        return $this->hasOne("\App\Models\Eloquent\Passeio", "idPasseio", "idPasseioOriginal");
    }

    public function local() {
        return $this->belongsTo("\App\Models\Eloquent\Local", "idLocal", "idLocal");
    }

    public function passeador() {
        return $this->belongsTo("\App\Models\Eloquent\Funcionario", "idPasseador", "idFuncionario");
    }

    public function cancelamentos() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento", "idPasseio", "idPasseio");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_cao_passeio", "idPasseio", "idCao")->withoutGlobalScope("ativo");
    }

    public function getDuracao($precisao = "H") {
        $diff = strtotime($this->fim) - strtotime($this->inicio);
        switch ($precisao) {
            case "s":
                return $diff;
            case "i":
            case "m":
                return $diff / 60;
            case "H":
                return $diff / 3600;
        }
    }

    public function getValor($cliente = null, $formatado = false) {
        $duracaoEmHoras = $this->getDuracao();
        if (!is_null($cliente)) {
            if (is_numeric($cliente)) {
                $cliente = Cliente::find($cliente);
            }
            $agendamentos = $this->agendamentos()->whereHas("cliente", function($q) use ($cliente) {
                        $q->where("idCliente", $cliente->idCliente);
                    })->get();
            if ($agendamentos->count() === 0) {
                return 0;
            }
        } else {
            $agendamentos = $this->agendamentos;
        }
        $valor = 0;
        foreach ($agendamentos as $agendamento) {
            $valor += $agendamento->precoPorCaoPorHora * $duracaoEmHoras * $this->getCaesDoCliente($agendamento->idCliente)->count();
        }
        if ($formatado) {
            return "R$ " . number_format($valor, 2, ",", ".");
        }
        return $valor;
    }

    public function getCaesDoCliente($cliente) {
        if (is_numeric($cliente)) {
            $cliente = (object) ["idCliente" => $cliente];
        }
        $agendamento = $this->agendamentos()->whereHas("cliente", function($q) use ($cliente) {
                    $q->where('idCliente', $cliente->idCliente);
                })->first();
        if (is_null($agendamento)) {
            return null;
        }
        return $agendamento->caes;
    }

    public function getCaesConfirmadosDoCliente($cliente) {
        if (is_numeric($cliente)) {
            $cliente = (object) ["idCliente" => $cliente];
        }
        return $this->caes()->whereHas("cliente", function($q) use ($cliente) {
                    $q->where("idCliente", $cliente->idCliente);
                })->get();
    }

    public function foiRemarcado() {
        return $this->passeioRemarcado()->count() > 0;
    }

    public function foiOriginado() {
        return $this->passeioOriginal()->count() > 0;
    }

    public function checarStatus($status) {
        if (!is_array($status)) {
            $status = [$status];
        }
        return in_array($this->status, $status);
    }

    public function getClientesConfirmados() {
        $ids = $this->caes->map(function($cao) {
            return $cao->idCao;
        });
        return Cliente::whereHas("caes", function($q) use ($ids) {
                    $q->whereIn("idCao", $ids->all());
                })->get();
    }
    
    public function getAgendamentoDoCliente($cliente) {
        if(is_numeric($cliente)) {
            $cliente = (object) [
                "idCliente" => $cliente
            ];
        }
        return $this->agendamentos()->whereHas("cliente", function($q) use ($cliente) {
            $q->where("idCliente", $cliente->idCliente);
        });
    }

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

    public function setInicioAttribute($value) {
        $this->attributes["inicio"] = date("H:i:s", strtotime($value));
    }

    public function setFimAttribute($value) {
        $this->attributes["fim"] = date("H:i:s", strtotime($value));
    }

    public function getClienteAttribute() {
        if (is_null($this->agendamento)) {
            return null;
        }
        return $this->agendamento->cliente;
    }

    public function getDonosAttribute() {
        $caes = $this->caes;
        $clientes = [];
        foreach ($caes as $cao) {
            $cliente = $cao->cliente;
            $clientes[$cliente->idCliente] = $cliente;
        }
        return array_values($clientes);
    }

    public function getTipoAttribute() {
        if ($this->coletivo) {
            return "Passeio Coletivo";
        }
        return "Passeio Unitário";
    }

    public function temAgendamentoConfirmado($cliente = null) {
        return $this->whereHas("agendamentos", function($q) use ($cliente) {
                    $q->where("status", AgendamentoStatus::FEITO);
                    if (!is_null($cliente)) {
                        if (is_numeric($cliente)) {
                            $cliente = Cliente::find($cliente);
                        }
                        $q->where("idCliente", $cliente->idCliente);
                    }
                })->count() > 0;
    }

    public function scopeAgendamentoConfirmado($query, $cliente = null) {
        return $query->whereHas("agendamentos", function($q) use ($cliente) {
                    $q->where("status", AgendamentoStatus::FEITO);
                    if (!is_null($cliente)) {
                        if (is_numeric($cliente)) {
                            $cliente = Cliente::find($cliente);
                        }
                        $q->where("idCliente", $cliente->idCliente);
                    }
                });
    }

    public function scopePendente($query) {
        return $query->where("status", Status::PENDENTE);
    }

    public function scopeCancelado($query) {
        return $query->where("status", Status::CANCELADO);
    }

    public function scopeEmAndamento($query) {
        return $query->where("status", Status::EM_ANDAMENTO);
    }

    public function scopeFeito($query) {
        return $query->where("status", Status::FEITO);
    }

    public function scopeNaoCancelado($query) {
        return $query->where("status", "!=", Status::CANCELADO);
    }

    public function scopeNaoEncerrado($query) {
        return $query->where(function($q) {
                    $q->orWhere("status", Status::PENDENTE);
                    $q->orWhere("status", Status::EM_ANDAMENTO);
                });
    }

    public function scopeNaoEmAndamento($query) {
        return $query->where("status", "!=", Status::EM_ANDAMENTO);
    }

    public function scopeNaoFeito($query) {
        return $query->where("status", "!=", Status::FEITO);
    }

    public function scopeEmAnalise($query) {
        return $query->where("status", "=", Status::EM_ANALISE);
    }

    public function scopePriorizarPorStatus($query, $customPriorities = []) {
        $priorities = [Status::EM_ANALISE => 0, Status::EM_ANDAMENTO => 1, Status::PENDENTE => 2, Status::FEITO => 3, Status::CANCELADO => 4];
        $priorities = array_merge($priorities, $customPriorities);
        $strPriority = "";
        foreach ($priorities as $priority => $value) {
            if (is_null($value)) {
                continue;
            }
            $strPriority .= "WHEN '$priority' THEN $value \n";
        }
        return $query->orderBy(
                        \DB::raw("CASE coalesce(status, 'null')
                            $strPriority
                            WHEN 'null' THEN 4
                    END"), "ASC");
    }

    public function getDataFormatadaAttribute() {
        return date("d/m/Y", strtotime($this->data));
    }

    public function getInicioFormatadoAttribute() {
        return date("H:i", strtotime($this->inicio));
    }

    public function getFimFormatadoAttribute() {
        return date("H:i", strtotime($this->fim));
    }

    public function getStatusFormatadoAttribute() {
        if ($this->foiRemarcado()) {
            return "Remarcado";
        }
        return Status::format($this->status);
    }

    public function getPorteFormatadoAttribute() {
        if (is_null($this->porte)) {
            return "Não aplicável";
        }
        return Porte::format($this->porte);
    }

    public function getPasseadorFormatadoAttribute() {
        if (is_null($this->passeador)) {
            return "Não alocado";
        }
        return $this->passeador->nome;
    }

}
