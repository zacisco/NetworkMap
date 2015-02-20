<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if ($_SERVER["REQUEST_METHOD"] == "GET" || isset($_POST['obj_id']) && !preg_match("/^[1-9]\d{0,10}$/", $_POST['obj_id']) || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
mysql_unbuffered_query("UPDATE `".$db_name."`.`objects` SET
`desc` = '".mysql_real_escape_string($_POST['desc'])."' 
WHERE `objects`.`id` = '".$_POST['obj_id']."'");
?>