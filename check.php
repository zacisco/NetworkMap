<?php
header('content-type: text/html; charset=utf-8');
if (!isset($_POST['title'])) $_POST['title'] = "";
	else {
		if (!preg_match("/^[А-яёрстуфхцчшщъыьэюя\w- ()+:_,.]{0,100}$/i", $_POST['title'])) {
			if (!isset($message)) $message = 'Equipment Title contains wrong characters or very long.';
		}
	}
	if (!isset($_POST['ip'])) $_POST['ip'] = "";
	elseif (!preg_match("/^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/", $_POST['ip']) && !empty($_POST['ip'])) {
		if (!isset($message)) $message = 'IP address of equipment contains wrong characters.';
		else $message .= '\nIP address of equipment contains wrong characters.';
	}
	if (!isset($_POST['equip_type'])) $_POST['equip_type'] = "1";
	elseif (!preg_match("/^[1-7]$/", $_POST['equip_type'])) {
		if (!isset($message)) $message = 'Equipment type contains wrong characters.';
		else $message .= '\nEquipment type contains wrong characters.';
	}
	if (!isset($_POST['connect_type'])) $_POST['connect_type'] = "1";
	elseif (!preg_match("/^[1-3]$/", $_POST['connect_type'])) {
		if (!isset($message)) $message = 'Connection type contains wrong characters.';
		else $message .= '\nConnection type contains wrong characters.';
	}
	if (!isset($_POST['web']) || $_POST['web'] != 1) $_POST['web'] = "0";
	if (!isset($_POST['ping']) || $_POST['ping'] != 1) $_POST['ping'] = "0";
	if (!isset($_POST['desc'])) $_POST['desc'] = "";
	else $_POST['desc'] = mysql_real_escape_string($_POST['desc']);
	if (!isset($_POST['posX']) || !preg_match("/^-?\d{1,5}$/", $_POST['posX'])
&& !isset($_POST['posY']) || !preg_match("/^-?\d{1,5}$/", $_POST['posY'])) {
		if (!isset($message)) $message = 'Coordinates contains wrong characters, not set or very big.';
		else $message .= '\nCoordinates contains wrong characters, not set or very big.';
	}
?>