<?php

namespace App\Models\Eloquent;

class Multimidia extends \WGPC\Eloquent\Model {

    protected $table = "multimidia";
    protected $primaryKey = "idMultimidia";
    protected $fillable = [
        "descricao",
        "data",
        "arquivo",
        "tipo"
    ];
    protected $dates = ["data"];
    protected static $rules = [
        "data" => ["required", "date"],
        "arquivo" => ["required", "max:255", "string", "unique:multimidia,arquivo"],
        "tipo" => ["required", "in:imagem,video", "string"],
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

}
