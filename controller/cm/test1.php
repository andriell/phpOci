<?php
/**
 * User: User
 * Date: 06.02.2015
 * Time: 18:46
 */

use \db\oracle\CM;

$sql = 'SELECT owner, table_name  FROM dba_tables WHERE OWNER = :OWNER';
$r = CM::con()->execute($sql, array('OWNER' => 'FSA_ADMIN'));
print_r($r);

$sql = "UPDATE SP_FSA_REO.ESEP_DOCS SET FILE_DATA = :FILE_DATA WHERE ID = '424CA665-BE15-4CD2-9F5E-BC9DC65D5F3A'";
$param = array(
    'ID' => '424CA665-BE15-4CD2-9F5E-BC9DC65D5F3A',
    'FILE_DATA' => new \db\oracle\data_type\LobFile(ROOT_DIR . '\index.php'),
);
CM::con()->beginTransaction();
CM::con()->beginTransaction();

$r = CM::con()->execute($sql, $param);

var_export($r);
echo "\n";
var_export(CM::con()->commit());
echo "\n";
var_export(CM::con()->commit());
echo "\n";
var_export(CM::con()->commit());
echo "\n";

echo \db\SQLHelper::insertParam($sql, $param);








