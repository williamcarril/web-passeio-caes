<?php

namespace App\Models;

class Multimidia extends Model {
    protected $table = "multimidia";
    
    protected $primaryKey = "idMultimidia";
    
    protected $fillable = [
        "nome",
        "descricao",
        "data",
        "arquivo",
        "tipo"
    ];
    
    protected $dates = ["data"];

    protected static $rules = [
        "nome" => ["required", "max:70", "string"],
        "data" => ["required", "date"],
        "arquivo" => ["required", "max:255", "string"],
        "tipo" => ["required", "in:imagem,video", "string"]
    ];
    
    public function setDataAttribute($value) {
        $value = str_replace("/", "-", $value);
        $this->attributes["data"] = date("Y-m-d", strtotime($value));
    }
    
    public static function boot() {
        parent::boot();
        static::$complexRules = [
            "arquivo" => [
                "rules" => ["image"],
                "check" => function($model) {
                    return $model->tipo === "imagem";
                }
            ],
            "arquivo" => [
                "rules" => ["mimes:mp4,avi,wmv"],
                "check" => function($model) {
                    return $model->tipo === "video";
                }
            ]
        ];
    }
    
}
