<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if ($_SERVER["REQUEST_METHOD"] == "GET" || isset($_POST['obj_id']) && !preg_match("/^[1-9]\d{0,10}$/", $_POST['obj_id']) || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
if ((!isset($_POST['posX']) || !preg_match("/^-?\d{1,5}$/", $_POST['posX'])) && (!isset($_POST['posY']) || !preg_match("/^-?\d{1,5}$/", $_POST['posY']))) die();
mysql_unbuffered_query("UPDATE `".$db_name."`.`objects` SET
`posX` = '".$_POST['posX']."',
`posY` = '".$_POST['posY']."'
WHERE `objects`.`id` = '".$_POST['obj_id']."'");
?>