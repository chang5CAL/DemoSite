<?php
error_reporting( E_ALL & ~E_DEPRECATED);
define ('DBPATH','localhost');
define ('DBUSER','kaseify16uz');
define ('DBPASS','somaz007');
define ('DBNAME','kaseify16');

session_start();

global $dbh;
$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
mysql_selectdb(DBNAME,$dbh);

error_reporting( E_ALL & ~E_DEPRECATED);

?>