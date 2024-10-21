<?php

namespace core\base\controller;

use core\base\exception\RouteException;
use core\base\settings\Settings;

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
                "inputMethod" => $this->InputMethod,
                "outputMethod" => $this->OutputMethod,
            ];
            $object->invoke(new $controller, $args);
        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args)
    {

        $this->parameters = $args["parameters"];

        $inputData = $args["inputMethod"];
        $outputData = $args["outputMethod"];

        $data = $this->$inputData();

        if (method_exists($this, $outputData)) {
            $page = $this->$outputData($data);
            if($page) $this->page = $page;
        } elseif ($data) {
            $this->page = $data;
        }


        /*    if ($this->errors) {
            $this->writeLog();
        } */
        $this->getPage();
    }
    protected function render($path = "", $parameters = [])
    {
        extract($parameters);
        if (!$path) {

            $class = new \ReflectionClass($this);

            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            $routes = Settings::get('routes');

            if($space === $routes["user"]["path"]) $template = TEMPLATE;
            else $template = ADMIN_TEMPLATE;

            $path = $template . explode("controller", strtolower($class->getShortName()))[0];
        }
        ob_start();
        if (!@include_once $path . '.php') throw new RouteException("Отсуствует шаблон - " . $path);
        return ob_get_clean();
    }
    protected function getPage()
    {
        if (is_array($this->page)) {
            foreach ($this->page as $block) echo $block;
        } else {
            echo $this->page;
        }
        exit();
    }
}
