<?php

if (!function_exists("repository")) {

    function repository($path = "") {
        $base = rtrim(config("path.repository"), "/") . "/";
        return asset("$base$path");
    }

}


if (!function_exists("postIncrement")) {

    function post_increment(&$value) {
        return $value++;
    }

}

if(!function_exists("pre_increment")) {
    function pre_increment(&$value) {
        return ++$value;
    }
}