<?php

namespace App\Models\Eloquent;

class Passeio extends \WGPC\Eloquent\Model {

    protected $table = "passeio";
    protected $primaryKey = "idPasseio";
    protected $fillable = [
        "idModalidade",
        "idTrajeto",
        "idPasseador",
        "preco",
        "inicio",
        "fim",
        "data",
        "status",
        "coletivo"
    ];
    protected static $rules = [
        "idModalidade" => ["required", "exists:modalidade,idModalidade", "integer"],
        "idTrajeto" => ["required", "exists:trajeto,idTrajeto", "integer"],
        "idPasseador" => ["exists:funcionario,idFuncionario,tipo,passeador", "integer"],
        "preco" => ["required", "numeric"],
        "coletivo" => ["boolean", "required"],
        "inicio" => ["required", "date_format:H:i:s", "less_or_equal:fim"],
        "fim" => ["required", "date_format:H:i:s", "greater_or_equal:inicio"],
        "data" => ["required", "date"],
        "status" => ["required", "in:pendente,cancelado,em_andamento,feito", "string"]
    ];
    protected $dates = ["data"];
    protected $casts = [
        "preco" => "float",
        "coletivo" => "boolean"
    ];
    protected $attributes = [
        "gravado" => false,
        "coletivo" => false,
        "status" => "pendente"
    ];

    public function trajeto() {
        return $this->belongsTo("\App\Models\Eloquent\Trajeto", "idTrajeto", "idTrajeto");
    }

    public function clientes() {
        $caes = $this->caes;
        $clientes = [];
        foreach ($caes as $cao) {
            $cliente = $cao->cliente;
            $clientes[$cliente->idCliente] = $cliente;
        }
        return array_values($clientes);
    }

    public function passeador() {
        return $this->belongsTo("\App\Models\Eloquent\Funcionario", "idPasseador", "idFuncionario");
    }

    public function cancelamento() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento", "idPasseio", "idPasseio");
    }

    public function caes() {
        return $this->belongsToMany("\App\Models\Eloquent\Cao", "a_cao_passeio", "idPasseio", "idCao");
    }

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

}
