<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";
if (isset($_GET['obj_id']) && !preg_match("/^[1-9]\d{0,10}$/", $_GET['obj_id']) || !check_auth($db_name)) {
	header("Location: ".BASEDIR);
	die();
}
$obj_result = mysql_query("SELECT * FROM `".$db_name."`.`objects` WHERE `objects`.`id`='".$_GET['obj_id']."'");
if (mysql_num_rows($obj_result) < 1) {
	header("Location: ".BASEDIR);
	die();
}
$data = mysql_fetch_assoc($obj_result);
mysql_free_result($obj_result);
$content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Description of '.$data['title'].'</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<script language="javascript" type="text/javascript" src="jscripts/post.js"></script>
<script language="javascript" type="text/javascript" src="jscripts/desc.js"></script>'."\n";

if (strpos($_SERVER['QUERY_STRING'], 'edit') !== false) {
	$content .= '<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme: "advanced",
	plugins : "table,visualchars,advimage,advlink,insertdatetime,preview,searchreplace,print,paste,directionality,fullscreen",

	// Theme options
	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,visualchars,|,sub,sup,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
});
</script>'."\n";
}

$content .='</head>
<body onload="javascript:load()">
<table border="0" align="center" width="40%">
	<caption><H2>'.$data['title'].'</H2></caption>
	<tr>
		<td>IP Address</td>
		<td>';
		if (!strlen($data['ip'])) $content .= 'No';
		else $content .= $data['ip'];
		$content .= '</td>
		</tr>
		<tr>
			<td>Web Control Panel</td>
			<td>';
	if ($data['web']) $content .= '<a href="http://'.$data['ip'].'/" target="'.$data['title'].'">Yes</a>';
	else $content .= 'No';
	if ($data['ping']) {
		$content .= '</td>
		</tr>
		<tr>
			<td>Status</td>
			<td id="'.$data['ip'].'"><font color="#FF0000">Offline</font>';
	}
	$content .= '</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><br><b>Description:</b></td>
	</tr>'."\n";

if (strpos($_SERVER['QUERY_STRING'], 'edit') === false) {
	$content .= '	<tr>
	<td colspan="2">'.$data['desc'].'</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input id="edit" type="button" value="Edit Description" onclick="action(this.id)"/>
		</td>
	</tr>'."\n";
} else {
	$content .= '	<tr>
		<td colspan="2">
			<textarea id="desc" rows="20%">
				'.htmlspecialchars($data['desc']).'
			</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input id="save" type="button" value="Save Description" onclick="action(this.id)"/>
		</td>
	</tr>'."\n";
}
$content .= '</table>
<input id="obj_id" type="hidden" size="11" maxlength="11" value="'.$data['id'].'"/>
</body>
</html>';
echo $content;
?>