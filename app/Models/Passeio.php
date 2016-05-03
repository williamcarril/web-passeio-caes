<?php

namespace App\Models;

class Passeio extends Model {

    protected $table = "passeio";
    protected $primaryKey = "idPasseio";
    protected $fillable = [
        "idModalidade",
        "idTrajeto",
        "idCliente",
        "idPasseador",
        "idMultimidia",
        "preco",
        "gravado",
        "inicio",
        "fim",
        "data",
        "status"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idTrajeto" => ["required", "exists:trajeto,idTrajeto", "integer"],
        "idCliente" => ["required", "exists:cliente,idCliente", "integer"],
        "idPasseador" => ["exists:funcionario,idFuncionario,tipo,passeador", "integer"],
        "idMultimidia" => ["exists:multimidia,tipo,video", "integer"],
        "preco" => ["required", "numeric"],
        "gravado" => ["boolean", "required"],
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "data" => ["required", "date"],
        "status" => ["required", "in:pendente,cancelado,em_andamento,feito", "string"]
    ];
    protected $dates = ["data"];
    protected $casts = [
        "preco" => "float",
        "gravado" => "boolean"
    ];
    protected $attributes = [
        "gravado" => false,
        "status" => "pendente"
    ];

    public function trajeto() {
        return $this->belongsTo("\App\Models\Trajeto", "idTrajeto", "idTrajeto");
    }

    public function cliente() {
        return $this->belongsTo("\App\Models\Cliente", "idCliente", "idCliente");
    }

    public function passeador() {
        return $this->belongsTo("\App\Models\Funcionario", "idPasseador", "idFuncionario");
    }

    public function video() {
        return $this->belongsTo("\App\Models\Multimidia", "idMultimidia", "idMultimidia");
    }

    public function cancelamento() {
        return $this->hasMany("\App\Models\Cancelamento", "idPasseio", "idPasseio");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Cao", "a_cao_passeio", "idPasseio", "idCao");
    }

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

}
