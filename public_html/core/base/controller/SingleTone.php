<?php

namespace core\base\controller;


trait SingleTone
{
    static private $__instance;


    private function __construct() {}

    private function __clone() {}

    static public function instance()
    {
        if (self::$__instance instanceof self) {
            return self::$__instance;
        }
        return self::$__instance = new self;
    }
}
