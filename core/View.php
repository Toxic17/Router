<?php

namespace Core;

class View
{
    private static string $path;
    private static array $data;
    public static function view(string $path,$data):string
    {
        self::$data = $data;

        self::$path = str_replace('public','',$_SERVER['DOCUMENT_ROOT']).'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,$path).'.php';
        return self::getContent();
    }

    private static function getContent():string
    {
        extract(self::$data);
        $html = require_once self::$path;

        return $html;

    }


}