<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\CancelamentoStatus;

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
        "status" => CancelamentoStatus::PENDETE
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
        static::$rules["status"][] = "in:" . implode(",", CancelamentoStatus::getConstants());

        static::saving(function($model) {
            $model->data = date("Y-m-d H:i:s");
        }, 1);
    }

    public function passeio() {
        return $this->belongsTo("\App\Modelszz\Eloquent\Passeio", "idPasseio", "idPasseio");
    }

    public function pessoa() {
        return $this->morphTo();
    }

    public function setJustificativaAttribute($value) {
        $this->attributes["justificativa"] = trim($value);
    }

}
