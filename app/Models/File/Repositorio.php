<?php

namespace App\Models\File;

use \Illuminate\Filesystem\FilesystemManager;
use \Symfony\Component\HttpFoundation\File\UploadedFile;
use \Intervention\Image\ImageManager;

class Repositorio {

    const DEFAULT_IMAGE_QUALITY = 60;

    private $path;
    private $rules;
    private $complexRules;
    private $storage;
    private $imageManager;

    public function __construct(FilesystemManager $storage, ImageManager $imageManager, $path = null, $rules = [], $complexRules = []) {
        if (is_null($path)) {
            $path = config("path.repository");
        }
        $this->storage = $storage;
        $this->imageManager = $imageManager;
        $this->setRules($rules);
        $this->setComplexRules($complexRules);
        $this->setPath($path);
    }

    public function setPath($path) {
        $this->path = rtrim($path, "/") . "/";
    }

    public function getPath() {
        return $this->path;
    }

    public function getRules() {
        return $this->rules;
    }

    public function setRules($rules) {
        if (is_null($rules)) {
            $rules = [];
        }
        $this->rules = $rules;
    }

    public function getComplexRules() {
        return $this->complexRules;
    }

    public function setComplexRules($complexRules) {
        if (is_null($complexRules)) {
            $complexRules = [];
        }
        $this->complexRules = $complexRules;
    }

    /**
     * @todo
     */
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

        $validatorKey = "source";
        $validator = validator(["$validatorKey" => $source], ["$validatorKey" => $this->rules]);
        foreach ($this->complexRules as $validation) {
            $rules = $validation["rules"];
            $check = $validation["check"];
            $validator->sometimes("$validatorKey", $rules, $check);
        }

        if ($validator->fails()) {
            return false;
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
