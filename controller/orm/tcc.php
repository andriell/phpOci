<?php
/**
 * User: User
 * Date: 13.02.2015
 * Time: 17:44
 */

use \orm\CC;

$str = 'Very_LONG_____stRINg_1_s_T_r';

echo 'Str: ' . $str . "\n\n";
echo 'lCC: ' . CC::lCC($str) . "\n";
echo 'uCC: ' . CC::uCC($str) . "\n";
echo 'lUS: ' . CC::lUS($str) . "\n";
echo 'uUS: ' . CC::uUS($str) . "\n";