<?php
/**
 * User: User
 * Date: 06.02.2015
 * Time: 18:55
 */

use \orm\Orm;

Orm::getInstance()
    ->setSchema(Config::schemaList())
    ->run();
