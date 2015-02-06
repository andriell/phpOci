<?php namespace orm; 
/**
 * User: User
 * Date: 06.02.2015
 * Time: 17:57
 */

class Orm {
    //<editor-fold desc="Singleton">
    /** @var Orm */
    private static $instance = null;
    private function __construct() {}
    /**
     * @return Orm
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    //</editor-fold>

    private $schema = array();

    /**
     * @param array $schema
     */
    public function setSchema($schema) {
        $this->schema = $schema;
    }

}
