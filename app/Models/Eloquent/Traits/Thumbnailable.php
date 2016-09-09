<?php

namespace App\Models\Eloquent\Traits;

trait Thumbnailable {

    public static function getDefaultThumbnail() {
        return asset("img/picture.png");
    }

    public function getThumbnailAttribute() {
        return static::getDefaultThumbnail();
    }

}
