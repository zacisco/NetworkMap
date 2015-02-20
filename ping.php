<?php
$ip = $_POST['ip'];
if (!isset($ip) || !preg_match("/^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/", $ip)) {
	//~ header("Location: form.php");
	die();
}

require_once "maincore.php";

function ping($os, $simple, $target, $packets = 2, $timeout = 2) {
	if ($os) {
		if ($simple) {
			exec("ping -c ".$packets." -w ".$timeout." ".$target, $result, $status);
			return !$status;
		} else {
			//~ Open the socket
			$handle = fsockopen('udp://'.$target, 7, $errno, $errstr, $timeout);
			if ($handle) {
				//~ Set read timeout
				stream_set_timeout($handle, $timeout);
				//~ send somthing
				if (fwrite($handle, "echo this\n")) {
					//~ Try to read. the server will most likely respond with a "ICMP Destination Unreachable"
					//~ and end the read. But that is a responce!
					fread($handle, 1024);
					if (feof($handle)) $reply = true;
					else $reply = false;
				}
			}
			fclose($handle);
			return $reply;
		}
	} else {
		if ($simple) {
			exec("ping -n ".$packets." -w ".$timeout." ".$target, $result, $status);
			return !$status;
		} else {
			$type = "\x08";
			$code = "\x00";
			$checksum = "\x00\x00";
			$identifier = "\x00\x00";
			$seqNumber = "\x00\x00";
			$data = "DDoS";

			/* ICMP ping packet with a pre-calculated checksum */
			$package = $type.$code.$checksum.$identifier.$seqNumber.$data;
			$checksum = icmpChecksum($package); // Calculate the checksum
			$package = $type.$code.$checksum.$identifier.$seqNumber.$data;

			//~ $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
			$socket = socket_create(AF_INET, SOCK_RAW, 1);
			socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
			socket_connect($socket, $host, null);

			$ts = microtime(true);
			socket_send($socket, $package, strLen($package), 0);
			if (socket_read($socket, 255)) $result = true;
			else $result = false;
			socket_close($socket);
			return $result;
		}
	}
}

$json_string = array('status' => ping($os, $simple, $ip, $packets, $ping_timeout) ? $online : $offline);
echo json_encode($json_string);
?>