<?php
header('content-type: image/svg+xml');
require_once "maincore.php";
if (!check_auth($db_name)) header("Location: ".BASEDIR);
$content = '<?xml version="1.0" encoding="utf-8" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg
version="1.1"
baseProfile="full"
xmlns="http://www.w3.org/2000/svg" 
xmlns:xlink="http://www.w3.org/1999/xlink"
xmlns:ev="http://www.w3.org/2001/xml-events"';

$obj_result = mysql_query("SELECT * FROM `".$db_name."`.`objects`");
if (mysql_num_rows($obj_result) < 1) {
	$content .= ' onload="load()">
<script type="text/javascript"><![CDATA[
function load() {
	window.alert(\'No one Eqipment was not found\nYou will been redirect for ADD New Eqipment\')
	window.location.href = "form.php?add&firstrun"
}
]]></script>
<title>LAN Fast Control Access</title>
<rect id="background" width="100%" height="100%" fill="'.$bgcolor.'"/>'."\n\n";
	mysql_free_result($obj_result);
} else {
	$content .= ' id="root" zoomAndPan="disable" onload="load()">
<script type="text/javascript" xlink:href="jscripts/post.js"/>
<script type="text/javascript" xlink:href="jscripts/functions.js"/>
<title>LAN Fast Control Access</title>
<rect id="background" width="100%" height="100%" fill="'.$bgcolor.'"/>
<g id="box" transform="translate(500,500)">'."\n";
	$tmp = array();
	$obj_data = array();
	while ($data = mysql_fetch_assoc($obj_result)) {
		array_push($tmp, $data['id']);
		unset($data['id']);
		array_push($obj_data, $data);
	}
	mysql_free_result($obj_result);
	$obj_data = array_combine($tmp, $obj_data);

	if (count($obj_data) > 1) {
		$lnk_result = mysql_query("SELECT * FROM `".$db_name."`.`links` ORDER BY `links`.`parent` ASC");
		$lnk_data = array();
		while ($data = mysql_fetch_assoc($lnk_result)) {
			unset($data['id']);
			array_push($lnk_data, $data);
		}
		mysql_free_result($lnk_result);
		unset($tmp);

		for ($i = 1; $i < count($lnk_data); $i++) {
			if ($obj_data[$lnk_data[$i]['parent']]['equip_type'] == 1) {
				$width_p = $switch_width;
				$height_p = $switch_height;
			} elseif ($obj_data[$lnk_data[$i]['parent']]['equip_type'] == 2) {
				$width_p = $router_width;
				$height_p = $router_height;
			} elseif ($obj_data[$lnk_data[$i]['parent']]['equip_type'] == 3) {
				$width_p = $modem_width;
				$height_p = $modem_height;
			} elseif ($obj_data[$lnk_data[$i]['parent']]['equip_type'] == 4) {
				$width_p = $mc_width;
				$height_p = $mc_height;
			} elseif ($obj_data[$lnk_data[$i]['parent']]['equip_type'] == 5) {
				$width_p = $box_width;
				$height_p = $box_height;
			}
			if ($obj_data[$lnk_data[$i]['child']]['equip_type'] == 1) {
				$width_c = $switch_width;
				$height_c = $switch_height;
			} elseif ($obj_data[$lnk_data[$i]['child']]['equip_type'] == 2) {
				$width_c = $router_width;
				$height_c = $router_height;
			} elseif ($obj_data[$lnk_data[$i]['child']]['equip_type'] == 3) {
				$width_c = $modem_width;
				$height_c = $modem_height;
			} elseif ($obj_data[$lnk_data[$i]['child']]['equip_type'] == 4) {
				$width_c = $mc_width;
				$height_c = $mc_height;
			} elseif ($obj_data[$lnk_data[$i]['child']]['equip_type'] == 5) {
				$width_c = $box_width;
				$height_c = $box_height;
			}
			$content .= '<line id="'.$lnk_data[$i]['parent'].'-'.$lnk_data[$i]['child'].'" x1="'.($obj_data[$lnk_data[$i]['parent']]['posX']+$width_p/2).'" y1="'.($obj_data[$lnk_data[$i]['parent']]['posY']+$height_p/2).'" x2="'.($obj_data[$lnk_data[$i]['child']]['posX']+$width_c/2).'" y2="'.($obj_data[$lnk_data[$i]['child']]['posY']+$height_c/2).'"';
			if ($obj_data[$lnk_data[$i]['parent']]['connect_type'] == $obj_data[$lnk_data[$i]['child']]['connect_type']) {
				if ($obj_data[$lnk_data[$i]['parent']]['connect_type'] == 1) {
					if (!empty($UTP_color)) $content .= ' stroke="'.$UTP_color.'"';
					if (!empty($UTP_width_line)) $content .= ' stroke-width="'.$UTP_width_line.'"';
					if (!empty($UTP_dash)) $content .= ' stroke-dasharray="'.$UTP_dash.'"';
				} elseif ($obj_data[$lnk_data[$i]['parent']]['connect_type'] == 2) {
					if (!empty($optical_color)) $content .= ' stroke="'.$optical_color.'"';
					if (!empty($optical_width_line)) $content .= ' stroke-width="'.$optical_width_line.'"';
					if (!empty($optical_dash)) $content .= ' stroke-dasharray="'.$optical_dash.'"';
				} elseif ($obj_data[$lnk_data[$i]['parent']]['connect_type'] == 3) {
					if (!empty($modem_color)) $content .= ' stroke="'.$modem_color.'"';
					if (!empty($modem_width_line)) $content .= ' stroke-width="'.$modem_width_line.'"';
					if (!empty($modem_dash)) $content .= ' stroke-dasharray="'.$modem_dash.'"';
				}
			} else {
				if (!empty($UTP_color)) $content .= ' stroke="'.$UTP_color.'"';
				if (!empty($UTP_width_line)) $content .= ' stroke-width="'.$UTP_width_line.'"';
				if (!empty($UTP_dash)) $content .= ' stroke-dasharray="'.$UTP_dash.'"';
			}
			$content .= '/>'."\n";
		}
	}
	$content .= "\n";
	foreach ($obj_data as $key => $value) {
		if ($obj_data[$key]['equip_type'] == 1) {
			$width = $switch_width;
			$height = $switch_height;
			$img = $switch_img;
		} elseif ($obj_data[$key]['equip_type'] == 2) {
			$width = $router_width;
			$height = $router_height;
			$img = $router_img;
		} elseif ($obj_data[$key]['equip_type'] == 3) {
			$width = $modem_width;
			$height = $modem_height;
			$img = $modem_img;
		} elseif ($obj_data[$key]['equip_type'] == 4) {
			$width = $mc_width;
			$height = $mc_height;
			$img = $mc_img;
		} elseif ($obj_data[$key]['equip_type'] == 5) {
			$width = $box_width;
			$height = $box_height;
			$img = $box_img;
		}
		$content .= '<image id="'.$key.'" x="'.$obj_data[$key]['posX'].'" y="'.$obj_data[$key]['posY'].'" width="'.$width.'" height="'.$height.'" xlink:href="img/'.$img.'"/>
<image id="add-'.$key.'" x="'.($obj_data[$key]['posX']+$width).'" y="'.$obj_data[$key]['posY'].'" width="'.($height/3).'" height="'.($height/3).'" xlink:href="img/'.$add.'"/>
<image id="edit-'.$key.'" x="'.($obj_data[$key]['posX']+$width).'" y="'.($obj_data[$key]['posY']+$height/3).'" width="'.($height/3).'" height="'.($height/3).'" xlink:href="img/'.$edit.'"/>
<image id="del-'.$key.'" x="'.($obj_data[$key]['posX']+$width).'" y="'.($obj_data[$key]['posY']+$height*2/3).'" width="'.($height/3).'" height="'.($height/3).'" xlink:href="img/'.$del.'"/>'."\n";
		if (strlen($obj_data[$key]['ip']) && $obj_data[$key]['ping'] == 1) {
			//~ if (ping($obj_data[$key]['ip'], $os, $simple, $packets, $ping_timeout)) $t_color = $u_color = $online;
			//~ else $t_color = $u_color = $offline;
			$t_color = $u_color = $offline;
		} else {
			$t_color = $title_color;
			$u_color = $url_color;
		}
		if (strlen($obj_data[$key]['title'])) {
			$content .= '<a xlink:href="http://'.$_SERVER['HTTP_HOST'].BASEDIR.'desc.php?obj_id='.$key.'" target="Description of '.$obj_data[$key]['title'].'">
	<text '.($obj_data[$key]['ping'] ? 'class="'.$obj_data[$key]['ip'].'" ' : '').'id="title-'.$key.'" x="'.($obj_data[$key]['posX']+$width/2).'" y="'.$obj_data[$key]['posY'].'" font-family="'.$title_font.'" font-size="'.$title_size.'" font-style="'.$title_style.'" fill="'.$t_color.'" text-anchor="middle">'.$obj_data[$key]['title'].'</text>
</a>'."\n";
		}
		//$content .= '<a xlink:href="http://'.$_SERVER['HTTP_HOST'].BASEDIR.'desc.php?obj_id='.$key.'" target="Description of '.$obj_data[$key]['title'].'">
	//<text id="desc-'.$key.'" x="'.($obj_data[$key]['posX']+$width/2).'" y="'.($obj_data[$key]['posY']+$height+10).'" font-family="'.$url_font.'" font-size="'.$url_size.'" font-style="'.$url_style.'" fill="'.$u_color.'" text-anchor="middle">Description</text>
//</a>'."\n";
		if ($obj_data[$key]['web'] == 1) {
			$content .= '<a xlink:href="http://'.$obj_data[$key]['ip'].'/" target="'.$obj_data[$key]['title'].'">
	<text '.($obj_data[$key]['ping'] ? 'class="'.$obj_data[$key]['ip'].'" ' : '').'id="url-'.$key.'" x="'.($obj_data[$key]['posX']+$width/2).'" y="'.($obj_data[$key]['posY']+$height+10).'" font-family="'.$url_font.'" font-size="'.$url_size.'" font-style="'.$url_style.'" fill="'.$u_color.'" text-anchor="middle">'.$obj_data[$key]['ip'].'</text>
</a>'."\n";
		} else if (strlen($obj_data[$key]['ip'])) {
			$content .= '<text '.($obj_data[$key]['ping'] ? 'class="'.$obj_data[$key]['ip'].'" ' : '').'id="url-'.$key.'" x="'.($obj_data[$key]['posX']+$width/2).'" y="'.($obj_data[$key]['posY']+$height+10).'" font-family="'.$url_font.'" font-size="'.$url_size.'" font-style="'.$url_style.'" fill="'.$u_color.'" text-anchor="middle">'.$obj_data[$key]['ip'].'</text>'."\n";
		}
		$content .= "\n";
	}
	$content .= '</g>';
}
$content .= "\n".'</svg>';
echo $content;
?>