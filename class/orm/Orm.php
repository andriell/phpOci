<?php namespace orm;
use db\oracle\CM;
use db\SQLHelper;

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
    private $nl = "\n";
    // Массив схем для которых будут производиться переименования
    private $schema = array();
    private $data = array();
    private $outputDir;
    private $namespace;

    /**
     * @param mixed $namespace
     * @return $this
     */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param mixed $outputDir
     * @return $this
     */
    public function setOutputDir($outputDir) {
        $this->outputDir = $outputDir;
        return $this;
    }

    /**
     * @param string $nl
     * @return $this
     */
    public function setNl($nl) {
        $this->nl = $nl;
        return $this;
    }

    /**
     * @param array $schema
     * @return $this
     */
    public function setSchema($schema) {
        $this->schema = $schema;
        return $this;
    }

    public function run() {
        File::getInstance()->clearDir($this->outputDir);
        $bind = array();
        $sql = "
SELECT atc.OWNER, atc.TABLE_NAME, ao.OBJECT_TYPE, atc.COLUMN_NAME, atc.DATA_TYPE
FROM SYS.ALL_OBJECTS ao
  JOIN SYS.ALL_TAB_COLUMNS atc ON ao.OWNER = atc.OWNER AND ao.OBJECT_NAME = atc.TABLE_NAME AND ao.OBJECT_TYPE IN ('TABLE', 'VIEW')
  WHERE ao.OWNER " . SQLHelper::makeIn($this->schema, $bind);
        $rTmp = CM::con()->execute($sql, $bind);
        $columnCount = 0;
        foreach ($rTmp as $row) {
            if (!isset($this->data[$row['OWNER']])) {
                $this->data[$row['OWNER']] = array(
                    C::param => array(),
                    C::data => array(),
                );
            }
            if (!isset($this->data[$row['OWNER']][C::data][$row['TABLE_NAME']])) {
                $this->data[$row['OWNER']][C::data][$row['TABLE_NAME']] = array(
                    C::param => array(
                        C::type => $row['OBJECT_TYPE'],
                    ),
                    C::data => array(),
                );
            }
            $this->data[$row['OWNER']][C::data][$row['TABLE_NAME']][C::data][$row['COLUMN_NAME']] = array(
                C::type => $row['DATA_TYPE'],
                C::asName => 'C' . ++$columnCount,
            );
        }
        foreach ($this->data as $schema => &$rowSchema) {
            foreach ($rowSchema[C::data] as $table => &$rowTable) {
                $this->genTableClass($schema, $table, $rowTable[C::data]);
            }
        }

        echo Helper::printR($this->data);
    }

    public function genTableClass($schema, $tableName, array &$cols) {
        $namespace = CC::uCC($schema);
        if ($this->namespace) {
            $namespace = $this->namespace . '\\' . $namespace;
        }

        $class = C::prefTb . CC::uCC($tableName);

        $str = '';
        $str .= '<?php namespace ' . $namespace . ';' . $this->nl;
        $str .= $this->nl;
        $str .= 'class ' . $class . ' {' . $this->nl;
        $str .= C::t1 . '// Column name' . $this->nl;
        foreach ($cols as $col => &$rowCol) {
            $str .= C::t1 . 'const ' . C::prefCN . CC::uCC($col) . ' = \'' . $col . '\';' . $this->nl;
        }
        $str .= $this->nl;
        $str .= C::t1 . '// Column as' . $this->nl;
        foreach ($cols as $col => &$rowCol) {
            $str .= C::t1 . 'const ' . C::prefColumnAs . CC::uCC($col) . ' = \'' . $rowCol[C::asName] . '\';' . $this->nl;
        }

        $str .= '}' . $this->nl;
        echo $str;

        File::getInstance()->saveClass($namespace, $class, $str);
    }
}
