<?php namespace orm; 
/**
 * User: User
 * Date: 06.02.2015
 * Time: 17:56
 */

class CamelCase {
    private static $lCC = array();
    private static $uCC = array();
    private static $lUS = array();
    private static $uUS = array();

    public static function lCC($str) {
        if (!isset(self::$lCC[$str])) {
            $str = strtolower($str);
            self::$lCC[$str] = preg_replace_callback('#(_+(\S))#si', function ($m) {
                return strtoupper($m[2]);
            }, $str);
        }
        return self::$lCC[$str];
    }

    public static function uCC($str) {
        if (!isset(self::$uCC[$str])) {
            $str = self::lCC($str);
            self::$uCC[$str] = strtoupper(substr($str, 0, 1)) . substr($str, 1);
        }
        return self::$uCC[$str];
    }

    public static function lUS($str) {
        if (!isset(self::$lUS[$str])) {
            $str = preg_replace('#_{2,}#', '_', $str);
            self::$lUS[$str] = strtolower($str);
        }
        return self::$lUS[$str];
    }

    public static function uUS($str) {
        if (!isset(self::$uUS[$str])) {
            $str = preg_replace('#_{2,}#', '_', $str);
            self::$uUS[$str] = strtoupper($str);
        }
        return self::$uUS[$str];
    }
} 