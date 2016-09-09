<?php

namespace App\Models\Eloquent\Traits;

trait Ativavel {

    public static function boot() {
        parent::boot();
        static::setStatusGlobalScope();
    }

    public static function setStatusGlobalScope() {
        static::addGlobalScope("ativo", function(\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where("ativo", true);
        });
    }

    public function getAtivoFormatadoAttribute() {
        return $this->ativo ? trans("field.ativo") : trans("field.inativo");
    }

}
