<?php

use core\base\controller\RouteController;
use core\base\exception\RouteException;

define("VG_ACCESS", true);
header("Content-type: text/html:charset-utf-8");
session_start();

require_once "config.php";
require_once "core/base/settings/internal_settings.php";
require "libraries/functions.php";

try {
<<<<<<< HEAD
    RouteController::getInstance()->route();  //<-  vizov staticheskogo metoda u klassa
    // RouteController::getInstance();
=======
// RouteController::getInstance()->route();  //<-  vizov staticheskogo metoda u klassa
    RouteController::getInstance();
>>>>>>> 8c6decf832a65828e15c62aa573b5b57ade0db19
} catch (RouteException $e) {
    exit($e->getMessage());
}
