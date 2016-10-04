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

if (!function_exists("pre_increment")) {

    function pre_increment(&$value) {
        return ++$value;
    }

}

if (!function_exists("str_lreplace")) {

    function str_lreplace($search, $replace, $subject) {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

}