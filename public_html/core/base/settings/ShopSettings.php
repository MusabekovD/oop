<?php

namespace core\base\settings;

use core\base\settings\Settings;

class ShopSettings
{
    static private $__instance;
    private $baseSettings;

    private $templateArr = [
        "text" => ["name", "phone", "address", "price", "short"],
        "textarea" => ["content", "keywords", "goods_content"]
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
        self::$baseSettings = Settings::instance();
        $baseProperties = self::$__instance->baseSettings->glueProperties(get_class());
        self::$__instance->setProperty($baseProperties);
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
    private function __construct() {}

    private function __clone() {}
}
