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

if (!function_exists("str_fix_article")) {

    function fix_article($text, $gender, $upperCase = false, $articleMark = "!{a}") {
        $article = "o(a)";
        switch ($gender) {
            case 0:
            case "male":
            case "macho":
                $article = "o";
                break;
            case 1:
            case "femea":
            case "fêmea":
            case "female":
                $article = "a";
                break;
            default:
                break;
        }
        if ($upperCase) {
            $article = strtoupper($article);
        }
        return preg_replace("/$articleMark/m", $article, $text);
    }

}