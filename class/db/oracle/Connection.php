<?php namespace db\oracle;
/**
 * User: User
 * Date: 14.01.2015
 * Time: 13:08
 */

use db\oracle\data_type\DataType;

class Connection {
    private $connection;
    /**
     * Счетчик открытых транзакций.
     * Сохранение происходит, когда закрывается столько же транзакций, сколько было открыто
     * @var int
     */
    private $openTransactionCount = 0;

    /**
     * @param string $username
     * @param string $password
     * @param string $connectionString
     * @param string $characterSet
     * @param int $sessionMode
     * @throws OracleException
     */
    function __construct($username, $password, $connectionString = null, $characterSet = null, $sessionMode = null) {
        $this->connection = oci_connect($username, $password, $connectionString, $characterSet, $sessionMode);
        if (!is_resource($this->connection)) {
            throw new OracleException();
        }
    }

    /**
     * Выполняет sql запрос с параметрами $param
     * Возвращает:
     * 1. Массив строк таблицы. Ключом этого массива может стать значения столбца $keyColumn если оно указанно
     * 2. Количество затронутых строк операцией insert или update
     *
     * @param $sql
     * @param $param
     * @param bool $keyColumn
     * @return array|int
     * @throws OracleException
     */
    public function execute($sql, $param = array(), $keyColumn = false) {
        $prepare = oci_parse($this->connection, $sql);
        if (!$prepare) {
            throw new OracleException($this->connection);
        }
        $paramsDataTypeKey = array();
        foreach ($param as $key => &$value) {
            if (is_object($value) && $value instanceof DataType) {
                $paramsDataTypeKey[] = $key;
                $value->onBind($this, $prepare, $key);
                continue;
            }
            oci_bind_by_name($prepare, $key, $value);
        }

        $execute = oci_execute($prepare, OCI_NO_AUTO_COMMIT);
        if (!$execute) {
            throw new OracleException($prepare);
        }
        $this->checkAndCommit();
        $return = array();
        while ($row = oci_fetch_array($prepare, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if ($keyColumn) {
                $return[$row[$keyColumn]] = $row;
            } else {
                $return[] = $row;
            }
        }
        if (empty($return)) {
            $return = oci_num_rows($prepare);
        }
        oci_free_statement($prepare);
        return $return;
    }

    //<editor-fold desc="Транзакции">
    /**
     * Закомитить если все транзакции закрыты
     * @return bool
     * @throws OracleException
     */
    protected function checkAndCommit() {
        if ($this->openTransactionCount <= 0) {
            $this->openTransactionCount = 0;
            $commit = oci_commit($this->connection);
            if (!$commit) {
                throw new OracleException($this->connection);
            }
            return true;
        }
        return false;
    }

    /**
     * Счетчик открытых транзакций.
     * Комит произойдет когда счетчик равен нулю.
     * @return int
     */
    public function getOpenTransactionCount() {
        return $this->openTransactionCount;
    }

    /**
     * Начать транзакцию
     */
    public function beginTransaction() {
        if ($this->openTransactionCount < 0) {
            $this->openTransactionCount = 0;
        }
        $this->openTransactionCount++;
    }

    /**
     * Закрыть транзакцию и закомитить если все транзакции закрыты
     * @return bool
     * @throws OracleException
     */
    public function commit() {
        $this->openTransactionCount--;
        if ($this->openTransactionCount < 0) {
            $this->openTransactionCount = 0;
        }
        return $this->checkAndCommit();
    }

    /**
     * Откатить все транзакции
     * @throws OracleException
     */
    public function rollback() {
        $rollback = oci_rollback($this->connection);
        if (!$rollback) {
            throw new OracleException($this->connection);
        }
        $this->openTransactionCount = 0;
    }
    //</editor-fold>

    /**
     * @param int $type
     * @return \OCI_Lob
     */
    public function newDescriptor($type = OCI_DTYPE_LOB) {
        $r = oci_new_descriptor($this->connection, $type);
        return $r;
    }
}
