<?php

namespace core\base\settings;

use core\base\controller\SingleTone;
use core\base\settings\Settings;

class ShopSettings


{

    use SingleTone;
     private $baseSettings;

     private $routes = [

        "plugins" => [
            "path" => "core/plugins",
            "hrUrl" => false,
            "dir" => false
        ],

    ];
    private $templateArr = [
        "text" => [ "price", "short"],
        "textarea" => ["goods_content"]
    ];

    static public function get($property)
    {
        return self::getInstance()->$property;
    }

    static private function getInstance()
    {
        if (self::$__instance instanceof self) {
            return self::$__instance;
        }
       
         self::instance()->baseSettings = Settings::instance();
         $baseProperties = self::$__instance->baseSettings->glueProperties(get_class());
         return self::$__instance;
    }
    protected function  setProperty($properties)
    {
        if ($properties) {
            foreach ($properties as $name => $property) {
                $this->$name = $property;
            }
        }
    }
}
