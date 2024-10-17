<?php

namespace core\base\settings;

use core\base\settings\Settings;

class ShopSettings
{
    static private $__instance;
     private $baseSettings;

    private $templateArr = [
        "text" => [ "price", "short"],
        "textarea" => ["goods_content"]
    ];

    static public function get($property)
    {
        return self::instance()->$property;
    }

    static public function instance()
    {
        if (self::$__instance instanceof self) {
            return self::$__instance;
        }
       
         self::$__instance = new self;
         self::$__instance->baseSettings = Settings::instance();
         $baseProperties = self::$__instance->baseSettings->glueProperties(get_class());
         return self::$__instance;
    }
    private function __construct() {}

    private function __clone() {}
}
