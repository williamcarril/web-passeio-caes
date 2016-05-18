<?php

namespace App\Models\Eloquent;

use Illuminate\Support\MessageBag;

class Model extends \Illuminate\Database\Eloquent\Model {

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Error message bag
     * @var Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Validation rules
     * @var Array
     */
    protected static $rules = [];

    /**
     * Complex validation rules
     * @var Array
     */
    protected static $complexRules = [];

    /**
     * Conjunto de regras de validação customizadas
     * @var array
     */
    protected static $customRules = [];

    /**
     * Custom messages
     * @var Array
     */
    protected static $messages = [];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->errors = new MessageBag();
    }

    /**
     * Listen for save event
     */
    protected static function boot() {
        parent::boot();

        static::saving(function($model) {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate() {
        $validator = \validator(
                $this->attributes, $this->overrideNormalRules(static::$rules), static::$messages
        );

        $validator->addExtensions($this->overrideCustomRules(static::$customRules));

        foreach ($this->overrideComplexRules(static::$complexRules) as $field => $validation) {
            $rules = $validation["rules"];
            $check = $validation["check"];
            $validator->sometimes($field, $rules, $check);
        }

        if ($validator->passes()) {
            return true;
        }
        $this->setErrors($validator->messages());
        return false;
    }

    /**
     * Retrieve error message bag
     * @return Illuminate\Support\MessageBag
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Sets error message bag
     * @param MessageBag $errors
     */
    public function setErrors(MessageBag $errors) {
        $this->errors = $errors;
    }

    /**
     * Inverse of wasSaved
     * @return boolean
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Clear error message bag
     */
    public function clearErrors() {
        $this->errors = new MessageBag();
    }

    public static function getRules() {
        return static::$rules;
    }

    public static function getCustomRules() {
        return static::$customRules;
    }

    public static function getComplexRules() {
        return static::$complexRules;
    }

    public static function getAllRules() {
        return [
            "rules" => static::getRules(),
            "custom" => static::getCustomRules(),
            "complex" => static::getComplexRules()
        ];
    }

    protected function overrideNormalRules($rules) {
        return $rules;
    }

    protected function overrideCustomRules($rules) {
        return $rules;
    }

    protected function overrideComplexRules($rules) {
        return $rules;
    }

}
