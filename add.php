<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if ($_SERVER["REQUEST_METHOD"] == "GET" || isset($_POST['obj_id']) && !preg_match("/^(?:[1-9]\d{0,10}|0)$/", $_POST['obj_id']) || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
require_once "check.php";
if (!isset($message)) {
	mysql_unbuffered_query("INSERT INTO `".$db_name."`.`objects`
(`id`, `title`, `ip`, `equip_type`, `connect_type`, `web`, `ping`, `desc`, `posX`, `posY`)
VALUES (
NULL, 
'".$_POST['title']."', 
'".$_POST['ip']."', 
'".$_POST['equip_type']."', 
'".$_POST['connect_type']."', 
'".$_POST['web']."', 
'".$_POST['ping']."', 
'".$_POST['desc']."', 
'".$_POST['posX']."', 
'".$_POST['posY']."');");

	if (!$_POST['obj_id']) $last_id = $_POST['obj_id'];
	else $last_id = mysql_insert_id();
	mysql_unbuffered_query("INSERT INTO `".$db_name."`.`links` (`id`, `parent`, `child`)
VALUES (NULL, '".$_POST['obj_id']."', '".$last_id."');");
	$message = 'Equipment was set up successfully';
} else $message .= '\nTry Again.';
require_once "footer.php";
?>