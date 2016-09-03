<?php

namespace App\Models\Eloquent\Traits;

trait Thumbnail {

    public static function getDefaultThumbnail() {
        return asset("img/picture.png");
    }

    public function getThumbnailAttribute() {
        return static::getDefaultThumbnail();
    }

}
