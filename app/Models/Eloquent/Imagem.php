<?php

namespace App\Models\Eloquent;

class Imagem extends \WGPC\Eloquent\Model {

    protected $table = "imagem";
    protected $primaryKey = "idImagem";
    protected $fillable = [
        "descricao",
        "data",
        "arquivo"
    ];
    protected $dates = ["data"];
    protected static $rules = [
        "data" => ["required", "date"],
        "arquivo" => ["required", "max:255", "string", "unique:imagem,arquivo"],
        "descricao" => ["string"]
    ];

    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }

    public static function boot() {
        parent::boot();
        static::saving(function($model) {
            $model->data = date("Y-m-d H:i:s");
        }, 1);
    }

    public function getUrl() {
        return repository("$this->arquivo");
    }

}
