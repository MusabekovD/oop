<?php

namespace core\base\settings;

use core\base\controller\SingleTone;

class Settings
{

    use SingleTone;

    private $routes = [
        "admin" => [
            "alias" => "admin",
            "path" => "core/admin/controller/",
            "hrUrl" => false,
        ],
        "settings" => [
            "path" => "core/base/settings/"
        ],
        "plugins" => [
            "path" => "core/plugins",
            "hrUrl" => false,
            "dir" => false
        ],
        "user" => [
            "path" => "core/user/controller/",
            "hrUrl" => true,
            "routes" => [
                "catalog" => "site/hello/by"
            ],
        ],
        "default"  => [
            "controller" => "IndexController",
            "inputMethod" => "inputData",
            "outputMethod" => "outputData"
        ]

    ];
    private $defaultTable = 'articles';

    private $expansion = 'core/admin/expansion/';
    
    private $templateArr = [
        "text" => ["name", "phone", "address"],
        "textarea" => ["content", "keywords"]
    ];


    static public function get($property)
    {
        return self::instance()->$property;
    }


    public function glueProperties($class)
    {
        $baseProperties = [];

        foreach ($this as $name => $item) {
            $property  = $class::get($name);
            $baseProperties[$name] = $property;
            if (is_array($property) && is_array($item)) {
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            if (!$property) $baseProperties[$name] = $this->$name;
        }
        return $baseProperties;
    }

    public function arrayMergeRecursive()
    {
        $arrays = func_get_args();
        $base = array_shift($arrays);

        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {

                if (is_array($value) && is_array($base[$key])) {
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                } else {
                    if (is_int($key)) {
                        if (!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }
}
