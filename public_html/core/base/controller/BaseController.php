<?php

namespace core\base\controller;

use core\base\exception\RouteException;

abstract class BaseController
{
    protected $page;
    protected $errors;
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
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args)
    {

        $this->parameters = $args["parameters"];

        $inputData = $args["InputMethod"];
        $outputData = $args["OutputMethod"];

        $this->$inputData();

        $this->page = $this->$outputData();

        if ($this->errors) {
            $this->writeLog();
        }
        $this->getPage();
    }
    protected function render($path = "", $parameters = [])
    {
        extract($parameters);
        if (!$path) {
            $path = TEMPLATE . explode("controller", strtolower((new \ReflectionClass($this))->getShortName()))[0];

            ob_start();
            if (! @include_once $path . '.php') throw new RouteException("Отсуствует шаблон - " . $path);
            return ob_get_clean();
        }
    }
    protected function getPage()
    {
        exit($this->page);
    }
}
