<?php namespace db;
/**
 * User: User
 * Date: 11.12.2014
 * Time: 17:38
 */

use db\oracle\data_type\DataType;

class SQLHelper {
    private static $inCount = 0;

    /**
     * Формирует строку IN (:IN1_1E, :IN1_2E, :IN1_3E)
     * Из массива $params, где
     *  $key - ключи этого массива
     * И добавляет значение в массив $bind
     * @param $params
     * @param $bind
     * @return string
     */
    public static function makeIn($params, &$bind) {
        self::$inCount++;
        $r = ' IN (';
        $p = '';
        $i = 0;
        foreach ($params as $val) {
            $i++;
            $key = ':IN' . self::$inCount . '_' . $i . 'E';
            $bind[$key] = $val;
            $r .= $p . $key;
            $p = ', ';
        }
        return $r . ')';
    }

    /**
     * Подставляет параметры в sql запрос.
     * Использовать можно только для логирования
     * @param $sql
     * @param array $param
     * @return string
     */
    public static function insertParam($sql, $param = array()) {
        if (!is_array($param)) {
            return $sql;
        }
        foreach ($param as $key => &$value) {
            if (substr($key, 0, 1) != ':') {
                $key = ':' . $key;
            }
            if (is_object($value) && $value instanceof DataType) {
                $str = get_class($value) . ': ' . $value->__toString();
            } else {
                $str = $value;
            }
            $str = str_replace("'", "''", $str);
            $sql = str_replace($key, '\'' . $str . '\'', $sql);
        }
        return trim($sql);
    }
}
