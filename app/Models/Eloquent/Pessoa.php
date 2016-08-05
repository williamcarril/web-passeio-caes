    <?php

namespace App\Models\Eloquent;

class Pessoa extends \WGPC\Eloquent\Model {
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
        "telefone" => ["max:12", "required", "numeric"],
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

}
