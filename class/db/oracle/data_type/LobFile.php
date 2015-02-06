<?php namespace db\oracle\data_type;
/**
 * User: User
 * Date: 14.01.2015
 * Time: 13:30
 */

use db\oracle\Connection;

class LobFile implements DataType {
    private $filePath;
    /** @var  \OCI_Lob */
    private $lobType;

    function __construct($filePath, $lobType = OCI_B_BLOB) {
        $this->filePath = $filePath;
        $this->lobType = $lobType;
    }

    /**
     * Вызывается при привязки переменных
     * @param Connection $connection
     * @param $prepare
     * @param $key
     * @return mixed
     */
    public function onBind(Connection &$connection, &$prepare, $key) {
        $lob = $connection->newDescriptor(OCI_D_LOB);
        $file = fopen($this->filePath, 'r');
        $fileData = fread($file, filesize($this->filePath));
        fclose($file);
        $lob->writeTemporary($fileData);
        oci_bind_by_name($prepare, $key, $lob, -1, $this->lobType);
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->filePath;
    }
}
