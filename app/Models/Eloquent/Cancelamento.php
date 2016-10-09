<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\CancelamentoStatus as Status;
use App\Models\Eloquent\Enums\FuncionarioTipo;

class Cancelamento extends \WGPC\Eloquent\Model {

    protected $primaryKey = "idCancelamento";
    protected $table = 'cancelamento';
    protected $fillable = [
        "idCancelamento",
        "idPessoa",
        "justificativa",
        "status",
        "data",
        "idPasseio",
        "tipoPessoa"
    ];
    protected static $rules = [
        "idPessoa" => ["required", "integer"],
        "justificativa" => ["required", "string"],
        "status" => ["required", "string"],
        "tipoPessoa" => ["required", "in:funcionario,cliente", "string"],
        "data" => ["required", "date"],
        "idPasseio" => ["required", "exists:passeio,idPasseio", "integer"],
    ];
    protected $attributes = [
        "status" => Status::PENDENTE
    ];
    protected $dates = ["data"];

    protected static function boot() {
        parent::boot();
        static::$complexRules = [
            "idPessoa" => [
                "rules" => ["exists:funcionario,idFuncionario"],
                "check" => function($model) {
            return $model->tipoPessoa === "funcionario";
        }
            ],
            "idPessoa" => [
                "rules" => ["exists:cliente,idCliente"],
                "check" => function($model) {
            return $model->tipoPessoa === "cliente";
        }
            ]
        ];
        static::$rules["status"][] = "in:" . implode(",", Status::getConstants());

        static::saving(function($model) {
            if(is_null($model->data)) {
                $model->data = date("Y-m-d H:i:s");
            }
        }, 1);
    }

    public function passeio() {
        return $this->belongsTo("\App\Models\Eloquent\Passeio", "idPasseio", "idPasseio");
    }

    public function pessoa() {
        switch ($this->tipoPessoa) {
            case "funcionario":
                return $this->belongsTo("App\Models\Eloquent\Funcionario", "idPessoa", "idFuncionario");
            case "cliente":
                return $this->belongsTo("App\Models\Eloquent\Cliente", "idPessoa", "idCliente");
        }
    }

    public function setJustificativaAttribute($value) {
        $this->attributes["justificativa"] = trim($value);
    }

    public function getDataFormatadaAttribute() {
        return date("d/m/Y", strtotime($this->data));
    }

    public function getHoraFormatadaAttribute() {
        return date("H:i:s", strtotime($this->data));
    }

    public function getTipoSolicitanteFormatadoAttribute() {
        switch ($this->tipoPessoa) {
            case "funcionario":
                $funcionario = $this->pessoa;
                return FuncionarioTipo::format($funcionario->tipo);
            case "cliente":
                return "Cliente";
        }
    }

    public function getStatusFormatadoAttribute() {
        return Status::format($this->status);
    }

    public function scopePriorizarPorStatus($query, $customPriorities = []) {
        $priorities = [Status::PENDENTE => 0, Status::VERIFICADO=> 1];
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

}
