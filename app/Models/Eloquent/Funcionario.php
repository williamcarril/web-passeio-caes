<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\FuncionarioTipo;

class Funcionario extends Pessoa {

    use Traits\Thumbnailable;

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        $this->fillable[] = "idImagem";
        $this->fillable[] = "rg";
        $this->fillable[] = "tipo";
        $this->guarded[] = "tipo";
    }

    public function imagem() {
        return $this->belongsTo("\App\Models\Eloquent\Imagem", "idImagem", "idImagem");
    }

    public function cancelamentos() {
        return $this->hasMany("\App\Models\Eloquent\Cancelamento", "idPessoa", "idFuncionario"
                )->where("tipoPessoa", "funcionario");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idPasseador", "idFuncionario");
    }

    public function limiteDeCaes() {
        return $this->hasMany("\App\Models\Eloquent\FuncionarioLimiteCaes", "idFuncionario", "idFuncionario");
    }

    public function getAuthIdentifierName() {
        return "idFuncionario";
    }

    protected function overrideNormalRules($rules) {
        $rules["rg"] = ["required", "string", "unique:funcionario,rg,{$this->idFuncionario},idFuncionario"];
        $rules["idImagem"] = ["exists:imagem,idImagem", "required", "integer"];
        $rules["tipo"] = ["required", "in:" . implode(",", FuncionarioTipo::getConstants()), "string"];
        
        $rules["email"][] = "unique:funcionario,email,{$this->idFuncionario},idFuncionario";
        $rules["cpf"][] = "unique:funcionario,cpf,{$this->idFuncionario},idFuncionario";
        return $rules;
    }

    public static function getDefaultThumbnail() {
        return asset("img/user.png");
    }

    public function getThumbnailAttribute() {
        return $this->imagem->getUrl();
    }

    public function setRgAttribute($value) {
        $rg = preg_replace('/[^0-9a-zA-Z]/', '', $value);
        $this->attributes["rg"] = $rg;
    }

    public function scopePasseador($query) {
        return $query->where('tipo', "passeador");
    }

    public function scopeAdministrador($query) {
        return $query->where('tipo', "administrador");
    }

    public function conflitaComSeusPasseios($passeioChecado) {
        $passeios = $this->passeios()->daData($passeioChecado->data)->get();
        if ($passeios->count() === 0) {
            return false;
        }
        return $passeioChecado->conflitaCom($passeios);
    }

    public function getLimiteDeCaes($porte) {
        $limiteDeCaes = $this->limiteDeCaes()->where("porte", $porte)->first();
        if (is_null($limiteDeCaes)) {
            return null;
        }
        return $limiteDeCaes->limite;
    }

}
