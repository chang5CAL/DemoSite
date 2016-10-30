<?php 
require_once('config.php');

$query = mysql_query("update userdata set online = 0 where id = '".$_SESSION['id']."'");
session_unset($_SESSION['id']);

echo '<script>window.location="login.php"</script>';



?>

