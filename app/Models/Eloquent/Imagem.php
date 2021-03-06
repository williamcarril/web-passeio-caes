<?php

namespace App\Models\Eloquent;

class Imagem extends \WGPC\Eloquent\Model {

    protected $table = "imagem";
    protected $primaryKey = "idImagem";
    protected $fillable = [
        "nome",
        "data"
    ];
    protected $dates = ["data"];
    protected static $rules = [
        "data" => ["required", "date"],
        "nome" => ["string", "max:75"]
    ];

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            if(is_null($model->data)) {
                $model->data = date("Y-m-d H:i:s");
            }
        }, 1);
    }

    public function getUrl($tamanho = null) {
        if (!is_null($tamanho)) {
            $arquivo = $this->arquivos()->where("tamanho", $tamanho)->first();
        } else {
            $arquivo = $this->arquivos()->orderBy(
                            \DB::raw("CASE coalesce(tamanho, 'null')
                                WHEN 'null' THEN 0
                                WHEN 'mobile' THEN 1
                                WHEN 'desktop' THEN 2
                    END"), "ASC")->first();
        }
        if (is_null($arquivo)) {
            return null;
        }
        return $arquivo->getUrl();
    }

    public function arquivos() {
        return $this->hasMany("App\Models\Eloquent\ImagemArquivo", "idImagem", "idImagem");
    }

}
