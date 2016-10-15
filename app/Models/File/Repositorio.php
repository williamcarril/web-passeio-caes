<?php

namespace App\Models\File;

use \Illuminate\Filesystem\FilesystemManager;
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use \Intervention\Image\ImageManager;

class Repositorio {

    const DEFAULT_IMAGE_QUALITY = 60;

    private $path;
    private $storage;
    private $imageManager;

    public function __construct(FilesystemManager $storage, ImageManager $imageManager, $path = null) {
        if (is_null($path)) {
            $path = config("path.repository");
        }
        $this->storage = $storage;
        $this->imageManager = $imageManager;
        $this->setPath($path);
    }

    public function setPath($path) {
        $this->path = rtrim($path, "/") . "/";
    }

    public function getPath() {
        return $this->path;
    }

    public function saveImage($source, $width = null, $height = null, $to = null) {
        try {
            $image = $this->imageManager->make($source);
            if (!empty($width) && !empty($height)) {
                $image->resize($width, $height);
            } else {
                if (!empty($width)) {
                    $image->widen($width);
                }
                if (!empty($height)) {
                    $image->heighten($height);
                }
            }
            $data = $image->encode("png", static::DEFAULT_IMAGE_QUALITY);
            if (is_null($to)) {
                $extOrPath = "png";
            } else {
                $extOrPath = $to;
            }
            return $this->save($data, $extOrPath);
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function saveTo($contents, $to) {
        $to = ltrim($to, "/");
        $toPath = "$this->path$to";
        $saved = $this->storage->put($toPath, $contents);
        if (!$saved) {
            return false;
        }
        return $to;
    }

    public function save($contents, $ext) {
        $to = uniqid(date("Y-m-d_")) . ".$ext";
        return $this->saveTo($contents, $to);
    }

    public function copy($source, $to = null) {
        if (is_string($source)) {
            $source = new \SplFileInfo($source);
        }

        if (is_null($to)) {
            if ($source instanceof \Illuminate\Http\UploadedFile) {
                $ext = $source->getClientOriginalExtension();
            } else {
                $ext = $source->getExtension();
            }
            return $this->save(file_get_contents($source), $ext);
        } else {
            return $this->saveTo(file_get_contents($source), $to);
        }
    }

    public function delete($filename) {
        return $this->storage->delete("$this->path$filename");
    }

}
