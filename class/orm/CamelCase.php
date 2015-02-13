<?php namespace orm; 
/**
 * User: User
 * Date: 06.02.2015
 * Time: 17:56
 */

class CamelCase {
    public static function lCC($str) {
        $str = strtolower($str);
        return preg_replace_callback('#(_+(\S))#si', function ($m) {
            return strtoupper($m[2]);
        }, $str);
    }

    public static function uCC($str) {
        $str = self::lCC($str);
        return strtoupper(substr($str, 0, 1)) . substr($str, 1);
    }

    public static function lUS($str) {
        $str = preg_replace('#_{2,}#', '_', $str);
        return strtolower($str);
    }

    public static function uUS($str) {
        $str = preg_replace('#_{2,}#', '_', $str);
        return strtoupper($str);
    }
} 