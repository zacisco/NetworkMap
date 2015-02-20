<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if (isset($_GET['obj_id']) && !preg_match("/^[1-9]\d{0,10}$/", $_GET['obj_id']) || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
$header = "";
$action = "";
$obj_id = "";
$title = "";
$ip = "";
$web = "";
$desc = "";
$posX = "";
$posY = "";
$ET_1 = "";
$ET_2 = "";
$ET_3 = "";
$ET_4 = "";
$ET_5 = "";
$ET_6 = "";
$ET_7 = "";
$CT_1 = "";
$CT_2 = "";
$CT_3 = "";
$parent = "";
$WCP = "";
$pinging = "";
if (strpos($_SERVER['QUERY_STRING'], 'firstrun') === false) {
	$obj_result = mysql_query("SELECT * FROM `".$db_name."`.`objects` WHERE `objects`.`id`='".$_GET['obj_id']."'");
	if (mysql_num_rows($obj_result) < 1) {
		header("Location: ".BASEDIR);
		die();
	} else $data = mysql_fetch_assoc($obj_result);
	mysql_free_result($obj_result);
}
if (strpos($_SERVER['QUERY_STRING'], 'add') !== false) {
	if (strpos($_SERVER['QUERY_STRING'], 'firstrun') == true) {
		$header = "Create First Eqipment";
		$obj_id = "0";
		$posX = "100";
		$posY = "200";
	} else {
		$header = "ADD New Eqipment";
		$obj_id = $data['id'];
		$posX = $data['posX'];
		$posY = $data['posY']+50;
	}
	$action = "add.php";
	$CT_1 = " selected";
	$name_but = "    Create    ";
} elseif (strpos($_SERVER['QUERY_STRING'], 'edit') !== false) {
	$action = "edit.php";
	$obj_id = $data['id'];
	$title = $data['title'];
	$header = "Edit Page for ".$data['title'];
	$ip = $data['ip'];
	if ($data['equip_type'] == 1) $ET_1 = ' selected';
	elseif ($data['equip_type'] == 2) $ET_2 = ' selected';
	elseif ($data['equip_type'] == 3) $ET_3 = ' selected';
	elseif ($data['equip_type'] == 4) $ET_4 = ' selected';
	elseif ($data['equip_type'] == 5) $ET_5 = ' selected';
	elseif ($data['equip_type'] == 6) $ET_6 = ' selected';
	elseif ($data['equip_type'] == 7) $ET_7 = ' selected';
	if ($data['connect_type'] == 1) $CT_1 = ' selected';
	elseif ($data['connect_type'] == 2) $CT_2 = ' selected';
	elseif ($data['connect_type'] == 3) $CT_3 = ' selected';

if ($_GET['obj_id'] != "1") {
	$parent .="\n".'<tr>
		<td>Connected to:</td>
		<td>
			<select id="parent" name="parent">'."\n";
		$obj_result = mysql_query("SELECT `id`,`title`, `ip`,`equip_type` FROM `".$db_name."`.`objects` WHERE `objects`.`id`<>'".$_GET['obj_id']."'");
		$obj_result2 = mysql_query("SELECT `parent` FROM `".$db_name."`.`links` WHERE `links`.`child`='".$_GET['obj_id']."'");
		$obj_result2 = mysql_fetch_assoc($obj_result2);
		$obj_data = array();
		$tmp = "";
		while ($obj_data = mysql_fetch_assoc($obj_result)) {
			if ($obj_data['equip_type'] == "1" || $obj_data['equip_type'] == "2" || $obj_data['equip_type'] == "3") $tmp = $obj_data['ip'];
			elseif ($obj_data['equip_type'] == "4") $tmp = "Media Converter";
			elseif ($obj_data['equip_type'] == "5") $tmp = "Optical Box";
			$parent .= '<option value="'.$obj_data['id'].'"'.(($obj_data['id'] == $obj_result2['parent'])?' selected':'').'>'.$obj_data['title'].' ('.$tmp.')</option>'."\n";
		}
		mysql_free_result($obj_result);
		unset($tmp);
		unset($obj_data);
		unset($obj_result2);
	$parent .= '</select>
		</td>
	</tr>'."\n";
}

	if ($data['web'] == 1) $WCP = ' checked';
	if ($data['ping'] == 1) $pinging = ' checked';
	$desc = $data['desc'];
	$name_but = "     Apply     ";
	$posX = $data['posX'];
	$posY = $data['posY'];
}

$content ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>'.$header.'</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
</head>
<script src="jscripts/form2.js" type="text/javascript"></script>
<body onload="javascript:check_all();">
<form action="'.$action.'" method="POST">
<table border="1" align="center">
	<caption><H2>'.$header.'</H2></caption>
	<tr>
		<td>Title:</td>
		<td>
			<input id="title" type="text" name="title" size="25" maxlength="50" value="'.$title.'"/>
			<img id="img_title" src="img/wrong.png" align="middle"/>
			<input id="tmp_ttl" type="hidden" value="'.$title.'"/>
		</td>
	</tr>
	<tr>
		<td>IP:</td>
		<td>
			<input id="ip" type="text" name="ip" size="15" maxlength="15" value="'.$ip.'"/>
			<img id="img_ip" src="img/wrong.png" align="middle"/>
			<input id="tmp_ip" type="hidden" value="'.$ip.'"/>
		</td>
	</tr>
	<tr>
		<td>Equipment:</td>
		<td>
			<select id="equip_type" name="equip_type" onchange="javascript:equip_check();">
				<option value="1"'.$ET_1.'>Switch</option>
				<option value="2"'.$ET_2.'>Router</option>
				<option value="3"'.$ET_3.'>Modem</option>
				<option value="4"'.$ET_4.'>Media Converter</option>
				<option value="5"'.$ET_5.'>Optical Box</option>
				<option value="6"'.$ET_6.'>PC</option>
				<option value="7"'.$ET_7.'>Printer</option>
				</select>
		</td>
	</tr>
	<tr>
		<td>Connection Type:</td>
		<td>
			<select id="connect_type" name="connect_type">
				<option value="1"'.$CT_1.'>Twisted Pair</option>
				<option value="2"'.$CT_2.'>Fibre Optics</option>
				<option value="3"'.$CT_3.'>xDSL</option>
			</select>
		</td>
	</tr>'.$parent.'
	<tr>
		<td>Web Control Panel:</td>
		<td>
			<input id="web" type="checkbox" name="web" value="1"'.$WCP.'/>
			<input id="tmp_web" type="checkbox" style="display: none"'.$WCP.'/>
		</td>
	</tr>
	<tr>
		<td>Ping to Object:</td>
		<td>
			<input id="ping" type="checkbox" name="ping" value="1"'.$pinging.'/>
			<input id="tmp_ping" type="checkbox" style="display: none"'.$pinging.'/>
		</td>
	</tr>
	<tr>
		<td>Description:</td>
		<td><textarea name="desc" cols="50%" rows="16">'.$desc.'</textarea></td>
	</tr>'."\n";
	$content .= '<tr>
	<td colspan="2" align="center"><input type="submit" id="button" value="'.$name_but.'"/></td>
	</tr>
</table>
<input type="hidden" name="obj_id" size="11" maxlength="11" value="'.$obj_id.'"/>
<input type="hidden" name="posX" size="5" maxlength="5" value="'.$posX.'"/>
<input type="hidden" name="posY" size="5" maxlength="5" value="'.$posY.'"/>
</form>
</body>
</html>';
echo $content;
?>