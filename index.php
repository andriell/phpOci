<?php
/**
 * User: User
 * Date: 19.12.2014
 * Time: 12:48
 */

use \db\oracle\CM;

header('Content-Type:text/plain;charset=utf-8');

define('ROOT_DIR', dirname(__FILE__));

include_once 'Config.php';
include_once 'class/__autoloader.php';

CM::setParam(Config::username, Config::password, Config::connectionString, Config::characterSet);

// Подключаем контроллер
$controller = $_SERVER["REQUEST_URI"];
$controller = str_replace(Config::baseUrl, '', $controller);
$controller = preg_replace('#[^0-9a-z_\-/]#si', '', $controller);
$controller = str_replace('/', DIRECTORY_SEPARATOR, $controller);
$controller = 'controller' . DIRECTORY_SEPARATOR . $controller . '.php';
if (file_exists($controller)) {
    include_once $controller;
} else {
    echo 'Нет контроллера: ' . $controller;
}


