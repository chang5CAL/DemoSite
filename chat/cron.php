<?php
require_once('config.php');
$res = mysql_query("SELECT * FROM `userdata` WHERE online=1 AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;");
if($res === FALSE){
    die(mysql_error());
}
while ($row = mysql_fetch_assoc($res)) {
    echo $q = "UPDATE `userdata` SET online = 0 WHERE id = {$row['id']};";
    $resupdate = mysql_query($q);
}


?>