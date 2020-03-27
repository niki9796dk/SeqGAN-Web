<?php declare(strict_types = 1);

class Dependencies
{
    public static function getStylesheets() {
        return [
            "libs/bootstrap/css/bootstrap.min.css",
            "css/general.css",
        ];
    }

    public static function getScripts() {
        return [
            "libs/jQuery/jquery-3.4.1.min.js",
            "libs/bootstrap/js/bootstrap.bundle.min.js",
            "js/dataTable.js",
            "js/clickable.js",
            "js/goodStar.js",
        ];
    }
}