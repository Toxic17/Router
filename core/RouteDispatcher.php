<?php
namespace Core;

use App\Http;

class RouteDispatcher{

    private RouteConfig $routeConfig;
    private string $requestUrl;
    private array $paramsArr = [];
    private array $paramsRequest = [];

    private static int $count_init = 0;

    private int $count_routes = 0;
    private $request;

    private Request $request;

    public function __construct($routeConfig,$count_routes,Request $request)

    {
        $this->routeConfig = $routeConfig;
        $this->count_routes = $count_routes;
        $this->request = $request;
    }

    public function checkUrl()
    {
        $this->requestUrl = $this->cleanUrl($this->request->getRequestTarget());
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
        if(strpos($this->requestUrl,"?")) {
            $this->requestUrl = substr($this->requestUrl, 0, strpos($this->requestUrl, "?"));
        }
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