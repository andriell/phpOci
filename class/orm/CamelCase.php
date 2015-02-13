<?php namespace orm; 
/**
 * User: User
 * Date: 06.02.2015
 * Time: 17:56
 */

class CamelCase {
    public static function lCC($str) {
        return preg_replace_callback('_\S', function ($m) {
            print_r($m);
        }, $str);
    }

    public static function uCC() {

    }

    public static function lUS() {

    }

    public static function uUS() {

    }
} 