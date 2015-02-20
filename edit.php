<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if ($_SERVER["REQUEST_METHOD"] == "GET" || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
if (isset($_POST['obj_id']) && !preg_match("/^[1-9]\d{0,10}$/", $_POST['obj_id'])) $message = 'WRONG ID Element.\nTry Again.';
else {
	require_once "check.php";
	if (!isset($message)) {
		mysql_unbuffered_query("UPDATE `".$db_name."`.`objects` SET
`title` = '".$_POST['title']."', 
`ip` = '".$_POST['ip']."', 
`equip_type` = '".$_POST['equip_type']."', 
`connect_type` = '".$_POST['connect_type']."', 
`web` = '".$_POST['web']."', 
`ping` = '".$_POST['ping']."', 
`desc` = '".$_POST['desc']."', 
`posX` = '".$_POST['posX']."', 
`posY` = '".$_POST['posY']."' 
WHERE `objects`.`id` = '".$_POST['obj_id']."'");
		mysql_unbuffered_query("UPDATE `".$db_name."`.`links` SET `parent` = '".$_POST['parent']."' WHERE `links`.`child` = '".$_POST['obj_id']."'");
		$message = 'Editing equipment was completed successfully';
	} else $message .= '\nTry Again.';
}
require_once "footer.php";
?>