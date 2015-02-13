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
            return 'Array(),';
        }
        $r = 'Array(' . self::nl;
        foreach ($array as $key => &$value) {
            if (is_array($array[$key])) {
                $r .= $tab . '    ' . self::exportToArray($key) . ' => ' . self::printR($array[$key], $tab . '    ', true) . self::nl;
            } else {
                $r .= $tab . '    ' . self::exportToArray($key) . ' => ' . self::exportToArray($value) . ',' . self::nl;
            }
        }
        return $r . $tab . '),';
    }

    protected  static function exportToArray($data) {
        if (is_int($data)) {
            return $data;
        } elseif (is_string($data)) {
            return '\'' . addslashes($data) . '\'';
        } elseif (is_bool($data)) {
            return $data ? 'true' : 'false';
        } elseif ($data === null) {
            return 'null';
        } elseif (is_object($data)) {
            return get_class($data);
        }
        return $data;
    }
} 