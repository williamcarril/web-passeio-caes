<?php

namespace App\Models;

class Pessoa extends Model {
    protected $fillable = [
        'nome',
        'telefone',
        "ativo",
        "cpf",
        "rua",
        "bairro",
        "postal",
        "numero",
        "lat",
        "lng",
        "email",
        "senha",
        "remember_token"
    ];
    protected $hidden = ['senha', 'remember_token'];
    protected static $rules = [
        "nome" => ["required", "max:70"],
        "telefone" => ["max:12", "required", "numeric"],
        "ativo" => ["boolean", "required"],
        "cpf" => ["cpf", "required"],
        "rua" => ["max:70", "required"],
        "bairro" => ["max:40", "required"],
        "postal" => ["cep", "required"],
        "numero" => ["max:12", "required"],
        "lat" => ["numeric", "required"],
        "lng" => ["numeric", "required"],
        "email" => ["required", "email"]
    ];

    public function setTelefoneAttribute($value) {
        $telefone = preg_replace('/[^0-9]/', '', $value);
        $this->attributes["telefone"] = $telefone;
    }

    public function setPostalAttribute($value) {
        $postal = preg_replace('/[^0-9]/', '', $value);
        $this->attributes["postal"] = $postal;
    }

    public function setCpfAttribute($value) {
        $cpf = preg_replace('/[^0-9]/', '', $value);
        $this->attributes["cpf"] = $cpf;
    }

}
