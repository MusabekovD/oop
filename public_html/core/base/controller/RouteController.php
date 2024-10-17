<?php

namespace core\base\controller;


use core\base\settings\Settings;
use core\base\settings\ShopSettings;
use Exception;

class RouteController 
{
    static private $_instance;

    protected $routes;
    protected $controller;
    protected $InputMethod;
    protected $OutputMethod;
    protected $parameters;

    private function __clone() {}

    static public function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

    private function __construct()
    {


        $address_str = $_SERVER["REQUEST_URI"];

        if (strrpos($address_str, "/") === strlen($address_str) - 1 && strrpos($address_str, "/") !== 0) {
            header("Location: " . rtrim($address_str, "/"), true, 301);

        }
        $path = substr($_SERVER["PHP_SELF"], 0, strpos($_SERVER["PHP_SELF"], 'index.php'));

        if ($path === PATH) {

            $this->routes = Settings::get("routes");
            if (!$this->routes) throw new \Exception("Сайт находиться на техническом обслуживании");

            // $url = explode("/", substr($address_str, strlen(PATH)));

            $trimmed_address = substr($address_str, strlen(PATH)); 
            $url = explode("/", trim($trimmed_address, "/")); // Use trim to avoid extra slashes
            var_dump($url);

            if ($url[0] && $url[0] === $this->routes["admin"]["alias"]) {
                // админка
                if ($url[0] && is_dir($_SERVER["DOCUMENT_ROOT"] . PATH . $this->routes["plugins"]["path"])) {

                    $plugin = array_shift($url);
                    $pluginSettings = $this->routes["settings"]["path"] . ucfirst($plugin . "Settings");

                    if (file_exists($_SERVER["DOCUMENT_ROOT"] . PATH . $pluginSettings . "php")) {
                        $pluginSettings = str_replace("/", "\\", $pluginSettings);
                        $this->routes = $pluginSettings::get("routes");
                    }
                    $dir = $this->routes["plugins"]["dir"] ? "/" . $this->routes["plugins"]["dir"] . "/" : "/";
                    $dir = str_replace("//", "/", $dir);

                    $this->controller = $this->routes["plugins"]["path"] . $plugin . $dir;
                    $hrUrl = $this->routes["plugins"]["hrUrl"];
                    $route = "plugins";
                } else {
                    $this->controller = $this->routes["admin"]["path"];
                    $hrUrl = $this->routes["admin"]["hrUrl"];
                    $route = "admin";
                }
            } else {
                $hrUrl = $this->routes["user"]["hrUrl"];

                $this->controller = $this->routes["user"]["path"];
                $route = "user";
            }
            $this->createRoute($route, $url);

            if ($url[1]) {
                $count = count($url);
                $key = "";

                if (!$hrUrl) {
                    $i = 1;
                } else {
                    $this->parameters["alias"] = $url[1];
                    $i = 2;
                }
                for (; $i < $count; $i++) {
                    if (!$key) {
                        $key = $url[$i];
                        $this->parameters[$key] = "";
                    } else {
                        $this->parameters[$key] = $url[$i];
                        $key = "";
                    }
                }
            }

            exit();
        } else {
            try {
                throw new \Exception("Не корректная директория сайта");
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
    }

   private function createRoute($var, $arr)
    {
        $route = [];
        if (!empty($arr[0])) {
            if ($this->routes[$var]["routes"][$arr[0]]) {
                $route = explode("/", $this->routes[$var]["routes"][$arr[0]]);

                $this->controller .= ucfirst($route[0] . "Controller");
            } else {
                $this->controller .= ucfirst($arr[0] . "Controller");
            }
        } else {
            $this->controller .= $this->routes["default"]["controller"];
        }
        $this->InputMethod = $route[1] ? $route[1] : $this->routes["default"]["InputMethod"];
        $this->OutputMethod = $route[1] ? $route[1] : $this->routes["default"]["OutputMethod"];
        return;
    } 
}