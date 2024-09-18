<?php

namespace Core;

class RouteConfig
{
    public string $url;
    public string $contrroller;
    public string $action;

    private string $name;
    private string $middelware;


    public function __construct(string $url,string $contrroller,string $action)
    {
        $this->url = $url;
        $this->action = $action;
        $this->contrroller = $contrroller;
    }

    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }

    public function middelware(string $middelware)
    {
        $this->middelware = $middelware;
        return $this;
    }



}

?>