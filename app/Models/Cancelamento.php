<?php

namespace App\Models;

class Cancelamento extends Model {

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
        "status" => ["in:pendente,feito", "string"],
        "tipoPessoa" => ["required", "in:funcionario,cliente", "string"],
        "data" => ["required", "date"],
        "idPasseio" => ["required", "exists:passeio,idPasseio", "integer"]
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
    }

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }
    
    public function passeio() {
        return $this->belongsTo("\App\Models\Passeio", "idPasseio", "idPasseio");
    }
    
    public function pessoa() {
        return $this->morphTo();
    }
}