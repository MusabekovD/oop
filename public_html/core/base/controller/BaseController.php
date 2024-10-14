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

        public funciton request($args){
            $this->parameters = $args["parameters"];

            $inputData = $args["inputData"];
            $outputData = $args["outputData"];

            $this->inputData();

            $this->page = $this->outputData();

            if($this->errors){
                $this->writelog();
            }
            $this->getPage();
        }
        protected function render($path = "", $parameters = []){
                extract($parameters);
                if(!path){
                    $path = TEMPLATE . explode("controller", strtolower( (new \ReflectionClass($this))->getShortName()))[0];
                }
        }
        protected function getPage(){
            exit($this->page);
        }

    }
}
