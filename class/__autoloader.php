<?php
/**
 * User: User
 * Date: 19.12.2014
 * Time: 12:44
 */

class Autoloader {
    private static $classDir = null;

    public static function getClassDir() {
        if (self::$classDir === null) {
            self::$classDir = dirname(__FILE__);
        }
        return self::$classDir;
    }

    /**
     * @param $className
     */
    public static function loadClass($className) {
        $filePath = self::getClassDir() . DIRECTORY_SEPARATOR . $className . '.php';
        include_once($filePath);
    }
}

spl_autoload_register(array('Autoloader', 'loadClass'));