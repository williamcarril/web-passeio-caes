<?php

namespace App\Models\Eloquent;

use App\Util\Formatter;

class Funcionario extends Pessoa {

    use Traits\Thumbnail;

    protected $primaryKey = "idFuncionario";
    protected $table = 'funcionario';

    public static function boot() {
        parent::boot();

        static::$rules["idImagem"] = ["exists:imagem,idImagem", "required", "integer"];
        static::$rules["rg"] = ["required", "string", "unique:funcionario,rg"];
        static::$rules["tipo"] = ["required", "in:passeador,administrador", "string"];
    }

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);

        $this->fillable[] = "idImagem";
        $this->fillable[] = "rg";
        $this->fillable[] = "tipo";
    }

    public function imagem() {
        return $this->belongsTo("\App\Models\Eloquent\Imagem", "idImagem", "idImagem");
    }

    public function cancelamentos() {
        return $this->morphMany("\App\Models\Eloquent\Cancelamento", "pessoa", "funcionario");
    }

    public function passeios() {
        return $this->hasMany("\App\Models\Eloquent\Passeio", "idFuncionario", "idPasseador");
    }

    public function getAuthIdentifierName() {
        return "idFuncionario";
    }

    protected function overrideNormalRules($rules) {
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

    public function scopePasseador($query) {
        return $query->where('tipo', "passeador");
    }

    public function scopeAdministrador($query) {
        return $query->where('tipo', "administrador");
    }

}
