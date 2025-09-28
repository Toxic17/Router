<?php

namespace Core;

class RequestFactory
{
    public static function createFromGlobals(): Request
    {
        return new Request(
            self::getProtocolVersion(),
            self::getHeadersFromGlobals(),
            new Stream(fopen('php://input', 'r')),
            $_SERVER['REQUEST_URI'] ?? '/',
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            self::createUriFromGlobals()
        );
    }

    private static function getProtocolVersion(): string
    {
        return isset($_SERVER['SERVER_PROTOCOL'])
            ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL'])
            : '1.1';
    }

    private static function getHeadersFromGlobals(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = [$value];
            }
        }
        return $headers;
    }

    private static function createUriFromGlobals(): Uri
    {
        $scheme = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        return new Uri("$scheme://$host$path");
    }

    public static function getQueryParams(): array
    {
        if(isset($_GET))
        {
            return $_GET;
        }
        return [];
    }

    public static function getPostParams(): array
    {
        if(isset($_POST))
        {
            return $_POST;
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input');
            return json_decode($input, true) ?? [];
        }

        $input = file_get_contents('php://input');
        if (!empty($input)) {
            parse_str($input, $result);
            return $result;
        }

        return [];
    }
}