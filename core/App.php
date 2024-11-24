<?php
namespace Core;
class App{
    public static function run()
    {

        $routeMethod = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));

        $routeString = 'getRoutes'.$routeMethod;

        if(!empty(Route::$routeString())) {
            foreach (Route::$routeString() as $routeConfiguration) {

                $dispatcher = new RouteDispatcher($routeConfiguration, count(Route::$routeString()));
                $dispatcher->checkUrl();
            }
        }
        else
        {
            require_once '../resources/404.php';
        }
    }
}

?>