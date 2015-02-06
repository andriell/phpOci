<?php namespace db\oracle;
/**
 * User: User
 * Date: 14.01.2015
 * Time: 12:59
 */

class CM {
    private static $username;
    private static $password;
    private static $connectionString = null;
    private static $characterSet = null;
    private static $sessionMode = null;

    /** @var Connection */
    private static $connection = null;

    private function __construct() {}

    /**
     * @param string $username
     * @param string $password
     * @param string $connectionString
     * @param string $characterSet
     * @param int $sessionMode
     */
    public static function setParam($username, $password, $connectionString = null, $characterSet = null, $sessionMode = null) {
        self::$username = $username;
        self::$password = $password;
        self::$connectionString = $connectionString;
        self::$characterSet = $characterSet;
        self::$sessionMode = $sessionMode;
    }

    /**
     * @return Connection
     */
    public static function con() {
        if (self::$connection === null) {
            self::$connection = new Connection(self::$username, self::$password, self::$connectionString, self::$characterSet, self::$sessionMode);
        }
        return self::$connection;
    }
}