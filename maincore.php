<?php
require_once "config.php";
//~ require_once "ping.php";

$temp = reverse_strrchr($_SERVER["SCRIPT_NAME"], '/', 1);
define("BASEDIR", strlen($temp) ? $temp : "/");
unset($temp);
$link = @mysql_connect($db_host, $db_user, $db_pass);
mysql_unbuffered_query("set character_set_client='utf8'");
mysql_unbuffered_query("set character_set_connection='utf8'");
mysql_unbuffered_query("set character_set_database='utf8'");
mysql_unbuffered_query("set character_set_results='utf8'");
mysql_unbuffered_query("set character_set_server='utf8'");
mysql_unbuffered_query("set character_set_system='utf8'");
mysql_unbuffered_query("set collation_connection='utf8_general_ci'");

function reverse_strrchr($haystack, $needle, $trail) {
	return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
}

function char_gen($len) {
	$str = "";
	for ($i = 0; $i < $len; $i++) {
		switch (rand(1,30)%3) {
			case 0:
				$str .= chr(rand(65,90));
				break;
			case 1:
				$str .= chr(rand(97,122));
				break;
			case 2:
				$str .= chr(rand(48,57));
				break;
		}
	}
	return $str;
}

function md5_salt($pass, $salt) {
	return md5(md5($pass).$salt);
}

function recursion_del($db_name, $id) {
	mysql_unbuffered_query("DELETE FROM `".$db_name."`.`objects` WHERE `objects`.`id` = '".$id."';");
	mysql_unbuffered_query("DELETE FROM `".$db_name."`.`links` WHERE `links`.`child` = '".$id."';");
	$obj_result = mysql_query("SELECT `child` FROM `".$db_name."`.`links` WHERE `links`.`parent`='".$id."';");
	if (mysql_num_rows($obj_result)) {
		while ($data = mysql_fetch_assoc($obj_result)) recursion_del($db_name, $data['child']);
	}
	mysql_free_result($obj_result);
}

function check_auth($db_name) {
	if (isset($_COOKIE["PHPSESSID"])) {
		$result = mysql_query("SELECT `session` FROM `".$db_name."`.`sessions` WHERE `user_ip`='".$_SERVER["REMOTE_ADDR"]."'");
		$data = mysql_fetch_array($result);
		mysql_free_result($result);
		if ($_COOKIE["PHPSESSID"] == $data['session']) return true;
	}
	return false;
}

function auth() {
	Header('WWW-Authenticate: Basic realm="Restricted Area"');
	Header('HTTP/1.0 401 Unauthorized');
	exit();
}

function login($db_name) {
	session_start();
	if (!isset($_SERVER['PHP_AUTH_USER'])) auth();
	else {
		$PHP_AUTH_USER = mysql_real_escape_string($_SERVER['PHP_AUTH_USER']);
		$result = mysql_query("SELECT `id`, `password`, `salt` FROM `".$db_name."`.`admins` WHERE `name`='".$PHP_AUTH_USER."'");
		$data = mysql_fetch_array($result);
		mysql_free_result($result);
		if ($data == NULL) auth();
		else {
			$pass_calc = md5_salt($_SERVER['PHP_AUTH_PW'], $data['salt']);
			if ($pass_calc != $data['password']) auth();
			else {
				if (mysql_num_rows(mysql_query("SELECT `id` FROM `".$db_name."`.`sessions` WHERE `user_ip`='".$_SERVER["REMOTE_ADDR"]."'"))) mysql_unbuffered_query("UPDATE `".$db_name."`.`sessions` SET `session`='".$_COOKIE["PHPSESSID"]."', `user_id`='".$data['id']."', `last_time`='".time()."' WHERE `user_ip`='".$_SERVER["REMOTE_ADDR"]."'");
				else mysql_unbuffered_query("INSERT INTO `".$db_name."`.`sessions` (`id`, `session`, `user_id`, `user_ip`, `last_time`) VALUES (NULL, '".$_COOKIE["PHPSESSID"]."', '".$data['id']."', '".$_SERVER["REMOTE_ADDR"]."', '".time()."')");
			}
		}
	}
	session_write_close();
}

function icmpChecksum($data) {
	if (strlen($data)%2) $data .= "\x00";
	//~ $bit = unpack('n*', $data);
	$sum = array_sum(unpack('n*', $data));
	while ($sum >> 16) $sum = ($sum >> 16) + ($sum & 0xffff);
	return pack('n*', ~$sum);
}
?>