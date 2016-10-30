<?php
error_reporting( E_ALL & ~E_DEPRECATED);
define ('DBPATH','localhost');
define ('DBUSER','root');
define ('DBPASS','');
define ('DBNAME','chat');

session_start();

global $dbh;
$dbh = mysql_connect(DBPATH,DBUSER,DBPASS);
mysql_selectdb(DBNAME,$dbh);

error_reporting( E_ALL & ~E_DEPRECATED);

?>