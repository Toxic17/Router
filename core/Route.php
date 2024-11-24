<?php
namespace Core;

use Core\RouteConfig;

class Route{

    public static array $routes_get = [];
    private static array $routes_post = [];

    public static function getRoutesGet()
    {
        return self::$routes_get;
    }

    public static function getRoutesPost()
    {

        return self::$routes_post;
    }

    public static function get(string $url,array $controller):RouteConfig
    {
        $route_config = new RouteConfig($url,$controller[0],$controller[1]);
        self::$routes_get[] = $route_config;

        
        return $route_config;
    }

    public static function post(string $url,array $controller):RouteConfig
    {
        $route_config = new RouteConfig($url,$controller[0],$controller[1],count(self::$routes_post)+1);
        self::$routes_post[] = $route_config;

        return $route_config;
    }

    public static function redirect($url)
    {
        header("Location:$url");
    }

}
?>