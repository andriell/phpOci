<?php namespace orm; 
/**
 * User: User
 * Date: 13.02.2015
 * Time: 18:42
 */

class File {
    //<editor-fold desc="Singleton">
    /** @var File */
    private static $instance = null;
    private function __construct() {
        $this->baseDir = ROOT_DIR . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR;
    }
    /**
     * @return File
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    //</editor-fold>

    private $baseDir;

    /**
     * @param string $baseDir
     */
    public function setBaseDir($baseDir) {
        $this->baseDir = $baseDir;
    }

    public function saveClass($namespace, $class, $data) {
        $split = preg_split('#\\\\#', $namespace);
        if (empty($split)) {
            $split = array($namespace);
        }

        $file = $this->baseDir;
        foreach ($split as $n) {
            $file .= $n . DIRECTORY_SEPARATOR;
            if (!is_dir($file)) {
                mkdir($file, 0777);
            }
        }
        $file .= $class . '.php';
        file_put_contents($file, $data);
    }

    function clearDir($dir) {
        if (!is_dir($dir)) {
            return;
        }
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object == '.' || $object == '..') {
                continue;
            }
            $object = $dir . DIRECTORY_SEPARATOR . $object;
            if (filetype($object) == 'dir') {
                $this->clearDir($object);
                rmdir($object);
            } else {
                unlink($object);
            }
        }
    }
}
