<?php
namespace Core;
use Core\Request;

class App{
    public static function run()
    {
        $request = new Request();
        $routeMethod = ucfirst($request->method());
        $routeString = 'getRoutes'.$routeMethod;

        if(!empty(Route::$routeString())) {
            foreach (Route::$routeString() as $routeConfiguration) {

                $dispatcher = new RouteDispatcher($routeConfiguration, count(Route::$routeString()),$request);
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