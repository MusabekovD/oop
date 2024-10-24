<?php

use core\base\controller\RouteController;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;

define("VG_ACCESS", true);
header("Content-type: text/html:charset-utf-8");
session_start();

require_once "config.php";
require_once "core/base/settings/internal_settings.php";
require "libraries/functions.php";

try {
   RouteController::instance()->route();  //<-  vizov staticheskogo metoda u klassa
   
} catch (RouteException $e) {
    exit($e->getMessage());
}
catch (DbException $e) {
    exit($e->getMessage());
}