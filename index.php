<?php
header('content-type: text/html; charset=utf-8');
require_once "maincore.php";

function click($db_name, $page) {
	if (!check_auth($db_name)) login($db_name);
	//require_once $page.".php";
	$content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Refresh" content="0; URL=\'http://'.$_SERVER['HTTP_HOST'].BASEDIR.$page.'.php\'">
</head>
<body>
</body>
</html>';
	echo $content;
}

if (strpos($_SERVER['QUERY_STRING'], 'map') !== false) click($db_name, 'net_graph');
else if (strpos($_SERVER['QUERY_STRING'], 'mgr') !== false) click($db_name, 'users_mgr');

if (check_auth($db_name)) click($db_name, 'net_graph');
else {
	$content ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>LAN Fast Control Access</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
</head>
<body>
<center>
<H2><a href="'.BASEDIR.'?map">NetworkMap</a></br>
<a href="'.BASEDIR.'?mgr">Users Manager</a></H2>
</center>
</body>
</html>';
	echo $content;
}
?>