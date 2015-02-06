<?php namespace db\oracle\data_type;
/**
 * User: User
 * Date: 14.01.2015
 * Time: 14:39
 */

use db\oracle\Connection;

interface DataType {
    /**
     * Вызывается при привязки переменных
     * @param Connection $connection
     * @param resource $prepare
     * @param string $key
     * @return mixed
     */
    public function onBind(Connection &$connection, &$prepare, $key);

    /**
     * @return string
     */
    public function __toString();
}
