<?php namespace db\oracle;
/**
 * User: User
 * Date: 14.01.2015
 * Time: 12:16
 */

use Exception;

class OracleException extends Exception {
    /**
     * @param bool|resource $resource
     * @param Exception $previous
     */
    public function __construct($resource = false, Exception $previous = null) {
        if (is_resource($resource)) {
            $arr = oci_error($resource);
        } else {
            $arr = oci_error();
        }
        $message = isset($arr['message']) ? $arr['message'] : print_r($arr, true);
        $code = isset($arr['code']) ? $arr['code'] : 0;
        parent::__construct($message, $code, $previous);
    }
}