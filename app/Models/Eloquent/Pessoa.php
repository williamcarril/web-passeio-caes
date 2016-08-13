<?php

namespace App\Models\Eloquent;

use Illuminate\Contracts\Auth\Authenticatable;

abstract class Pessoa extends \WGPC\Eloquent\Model implements Authenticatable {

    protected $attributes = [
        "ativo" => true
    ];
    protected $fillable = [
        'nome',
        'telefone',
        "ativo",
        "cpf",
        "logradouro",
        "bairro",
        "postal",
        "numero",
        "complemento",
        "lat",
        "lng",
        "email",
        "senha",
        "remember_token"
    ];
    protected $hidden = ['senha', 'remember_token'];
    protected $casts = [
        "lat" => "float",
        "lng" => "float",
        "ativo" => "boolean"
    ];
    protected static $rules = [
        "nome" => ["required", "max:70", "string"],
        "telefone" => ["max:11", "required", "string", "phone"],
        "ativo" => ["boolean", "required"],
        "cpf" => ["cpf", "required", "string"],
        "logradouro" => ["max:70", "required", "string"],
        "bairro" => ["max:40", "required", "string"],
        "postal" => ["cep", "required", "string"],
        "numero" => ["max:12", "required", "string"],
        "lat" => ["numeric", "required"],
        "lng" => ["numeric", "required"],
        "complemento" => ["max:50", "string"],
        "email" => ["required", "email", "string"]
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

    public function getAuthIdentifier() {
        return $this->attributes[$this->getAuthIdentifierName()];
    }

    public function getAuthPassword() {
        return $this->attributes["senha"];
    }

    public function getRememberToken() {
        return $this->attributes[$this->getRememberTokenName()];
    }

    public function getRememberTokenName() {
        return "remember_token";
    }

    public function setRememberToken($value) {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }

}
