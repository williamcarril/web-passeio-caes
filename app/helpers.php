<?php

if (!function_exists("repository")) {

    function repository($path = "") {
        $base = rtrim(config("path.repository"), "/") . "/";
        return asset("$base$path");
    }

}
