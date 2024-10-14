<?php

namespace core\base\controller;

use core\base\exception\RouteException;

abstract class BaseController
{
    protected $controller;
    protected $InputMethod;
    protected $OutputMethod;
    protected $parameters;

    public function route()
    {
        try {
            $controller = str_replace("/", "\\", $this->controller);
            $object = new \ReflectionMethod($controller, "request");

            $args = [
                "parameters" => $this->parameters,
                "InputMethod " => $this->InputMethod,
                "OutputMethod" => $this->OutputMethod,
            ];
            $object->invoke(new $controller, $args);
        } catch (\ReflectionException $e) {
            throw new RouteException($e);
        }
    }
}
