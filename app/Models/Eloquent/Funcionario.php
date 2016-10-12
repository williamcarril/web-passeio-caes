<?php

namespace App\Models\Eloquent;

use App\Models\Eloquent\Enums\FuncionarioTipo;

class Funcionario extends Pessoa {

    use Traits\Thumbnailable;

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    public static function boot() {
        parent::boot();

        static::$rules["idImagem"] = ["exists:imagem,idImagem", "required", "integer"];
        static::$rules["rg"] = ["required", "string"];
        static::$rules["tipo"] = ["required", "in:" . implode(",", FuncionarioTipo::getConstants()), "string"];
    }

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
        $rules["email"][] = "unique:funcionario,email,{$this->idFuncionario},idFuncionario";
        $rules["cpf"][] = "unique:funcionario,cpf,{$this->idFuncionario},idFuncionario";
        $rules["rg"][] = "unique:funcionario,rg,{$this->idFuncionario},idFuncionario";
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

    public function obterPasseiosDaData($data, $direcaoOrdemData = "asc") {
        $data = date("Y-m-d", strtotime(str_replace("/", "-", $data)));
        return $this->passeios()->where("data", $data)->orderBy("data", $direcaoOrdemData)->get();
    }

    public function conflitaComSeusPasseios($passeioChecado) {
        $passeios = $this->obterPasseiosDaData($passeioChecado->data);
        if($passeios->count() === 0) {
            return false;
        }
        foreach ($passeios as $passeio) {
            $inicio = "$passeio->data $passeio->inicio";
            $fim = "$passeio->data $passeio->fim";

            if ($passeioChecado->ocorreriaEntre($inicio, $fim)) {
                return true;
            }
        }
        return false;
    }
    
    public function getLimiteDeCaes($porte) {
        $limiteDeCaes = $this->limiteDeCaes()->where("porte", $porte)->first();
        if(is_null($limiteDeCaes)) {
            return null;
        }
        return $limiteDeCaes->limite;
    }

}
