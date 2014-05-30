<?php
require_once("config.php");
/** @var array $config */
$dbData = $config['dev']['DB'];
mysql_connect($dbData['hostname'], $dbData['username'], $dbData['password']);
mysql_select_db($dbData['database']);