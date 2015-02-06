<?php namespace orm; 
/**
 * User: User
 * Date: 06.02.2015
 * Time: 19:54
 */

class Helper {
    const nl = "\n";
    public static function printR(&$array, $tab = '', $isFirst = true) {
        if (!is_array($array)) {
            return $array . self::nl;
        }
        if (empty($array)) {
            return 'Array()';
        }
        $r = '';
        foreach ($array as $key => &$value) {
            if (is_array($array[$key])) {
                if ($isFirst) {
                    $r .= 'Array(' . self::nl;
                } else {
                    $r .= $tab . 'Array(' . self::nl;
                }
                $r .= $tab . "    [" . $key . "] => " . self::printR($array[$key], $tab . '    ', true) . self::nl;
                $r .= $tab . '),' . self::nl;
            } else {
                $r .= $tab . "    [" . $key . "] => " . $value . ',' . self::nl;
            }
            $isFirst = false;
        }
        return $r;
    }
} 