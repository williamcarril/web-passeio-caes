<?php

namespace App\Models\File;

use \Illuminate\Filesystem\FilesystemManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Repositorio {

    private $path;
    private $rules;
    private $complexRules;
    private $storage;

    public function __construct(FilesystemManager $storage, $path = null, $rules = [], $complexRules = []) {
        if (is_null($path)) {
            $path = config("path.repository");
        }
        $this->storage = $storage;
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

    public function save($source, $to = null) {
        if (is_string($source)) {
            $source = new SplFileInfo($source);
        }

        if (is_null($to)) {
            if ($source instanceof UploadedFile) {
                $to = uniqid(date("Y-m-d_")) . "." . $source->getClientOriginalExtension();
            } else {
                $to = uniqid(date("Y-m-d_")) . "." . $source->getExtension();
            }
        } else {
            $to = ltrim($to, "/");
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

        $contents = file_get_contents($source);
        $toPath = "$this->path$to";
        $saved = $this->storage->put($toPath, $contents);
        if (!$saved) {
            return false;
        }

        return $to;
    }

    public function delete($filename) {
        return $this->storage->delete("$this->path$filename");
    }

}
