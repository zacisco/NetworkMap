<?php
//~ #######################################
$db_host = "localhost";
$db_user = "dbuser";
$db_pass = "dbpass";
$db_name = "NetworkMap";

//~ For PING Function
$os = 1; // UNIX = 1, WIN = 0
$simple = 1; // For ping function. exec php function (using system PING programm) = 1, creation packet & send = 0
$packets = 3;
$ping_timeout = 3;
//~ For PING Function END

//~ Admin Configuration
$admin_name = 'admin';
$admin_pass = 'password';
$salt_len = 3; // 0-3
//~ Admin Configuration END

$bgcolor = '#AAAAAA';

$offline = '#FF0000';
$online = '#006400';

$title_font = 'Arial';
$title_size = 12;
$title_style = 'normal';
$title_color = '#000000';

$url_font = 'Arial';
$url_size = 12;
$url_style = 'normal';
$url_color = '#000000';

$switch_width = 50;
$switch_height = 40;
$switch_img = 'Switch.png';

$router_width = 40;
$router_height = 35;
$router_img = 'Router.png';

$modem_width = 40;
$modem_height = 35;
$modem_img = 'Modem.png';

$mc_width = 25;
$mc_height = 20;
$mc_img = 'MC.png';

$box_width = 25;
$box_height = 20;
$box_img = 'BOX.png';

$add = 'add.png';
$edit = 'edit.png';
$del = 'del.png';

//~ DASH syntax - left point size, center point size, right point size
//~ if empty - dash will be disable

$modem_width_line = 2.5;
$modem_color = '#FF0000';
$modem_dash = '5,5,5';

$UTP_width_line = 1;
$UTP_color = '#000000';
$UTP_dash = '';

$optical_width_line = 5;
$optical_color = '#FFFF00';
$optical_dash = '';
?>