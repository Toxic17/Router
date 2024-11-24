<?php
namespace Core;

use Core\RouteConfig;
use App\Http;

class RouteDispatcher{

    private RouteConfig $routeConfig;
    private string $requestUrl;
    private array $paramsArr = [];
    private array $paramsRequest = [];

    private static int $count_init = 0;

    private int $count_routes = 0;

    public function __construct($routeConfig,$count_routes)
    {
        $this->routeConfig = $routeConfig;
        $this->count_routes = $count_routes;

    }

    public function checkUrl()
    {


            $this->requestUrl = $this->cleanUrl($_SERVER['REQUEST_URI']);
            $this->routeConfig->url = $this->cleanUrl($this->routeConfig->url);

            $this->setParamMap();
            $this->makeRegexRequest();


    }

    public function cleanUrl($uri)
    {

        return preg_replace('/(^\/)|(\/$)/','',$uri);
    }

    public function setParamMap()
    {
        $routeArray = explode('/',$this->routeConfig->url);


        foreach ($routeArray as $paramKey => $param)
        {
            if(preg_match('/{.*}/',$param))
            {
                $this->paramsArr[$paramKey] = preg_replace('/(^{)|(}$)/','',$param);
            }
        }
    }

    public function makeRegexRequest()
    {
        $requestUriArray = explode('/',$this->requestUrl);

        foreach ($this->paramsArr as $paramKey => $paramValues)
        {
            if(isset($requestUriArray[$paramKey])) {
                $this->paramsRequest[$paramValues] = $requestUriArray[$paramKey];
                $requestUriArray[$paramKey] = '{.*}';
            }
        }
        $this->requestUrl = implode('/',$requestUriArray);
        $this->requestUrl = str_replace('/','\/',$this->requestUrl);
        $this->run();
    }

    private function run()
    {

        if(preg_match("/^$this->requestUrl$/",$this->routeConfig->url))
        {
            $this->render();
        }
        else
        {
            self::$count_init++;
        }
        if(self::$count_init == $this->count_routes)
        {
            require_once '../resources/404.php';
        }
    }

    public function render()
    {
        $controller = $this->routeConfig->contrroller;
        $action = $this->routeConfig->action;
        (new $controller)->$action(...$this->paramsRequest);
    }

}
?>