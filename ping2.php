<?php
require_once "config.php";
require_once "maincore.php";
if ($os) {
	if ($simple) {
		exec("ping -q -c ".$packets." -w ".$ping_timeout." ".$obj_data[$key]['ip'], $result, $status);
		if ($status == 0) return $online;
		else return $offline;
	} else {
		//~ Open the socket
		$handle = fsockopen('udp://'.$obj_data[$key]['ip'], 7, $errno, $errstr, $ping_timeout);
		if ($handle) {
			//~ Set read timeout
			stream_set_timeout($handle, $ping_timeout);
			//~ send somthing
			if (fwrite($handle, "echo this\n")) {
				//~ Try to read. the server will most likely respond with a "ICMP Destination Unreachable"
				//~ and end the read. But that is a responce!
				fread($handle, 1024);
				if (feof($handle)) $reply = $online;
				else $reply = $offline;
			}
		}
		fclose($handle);
		return $reply;
	}
} else {
	if ($simple) {
		exec("ping -q -n ".$packets." -w ".$ping_timeout." ".$obj_data[$key]['ip'], $result, $status);
		if ($status == 0) return $online;
		else return $offline;
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
		socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $ping_timeout, 'usec' => 0));
		socket_connect($socket, $host, null);

		$ts = microtime(true);
		socket_send($socket, $package, strLen($package), 0);
		if (socket_read($socket, 255)) $result = $online;
		else $result = $offline;
		socket_close($socket);
		return $result;
	}
}
?>