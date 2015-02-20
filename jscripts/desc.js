//~ if (window.addEventListener) { // Mozilla
	//~ window.addEventListener('load', addEvents, false)
//~ }
//~ else if (window.attachEvent) { // Other Browser
	//~ window.attachEvent('onload', addEvents)
//~ }

var obj_id

function load() {
	var TDs = document.getElementsByTagName("td")
	for (var i = 0; i < TDs.length; i++) {
		var ip = TDs[i].getAttribute('id')
		var reg_ip = /^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/
		var ip_result = reg_ip.test(ip)
		if (ip_result) {
			var send_post = "ip="+ip
			serverRequest("ping.php", send_post, answer, ip, 0)
		}
	}
}

function action(btn_id) {
	obj_id = document.getElementById('obj_id').getAttribute('value')
	if (btn_id == "edit") {
		window.location.href = "desc.php?edit&obj_id=" + obj_id
	} else if (btn_id == "save") {
		var send_post = "obj_id=" + obj_id + "&desc=" + tinyMCE.get('desc').getContent()
		serverRequest("save_desc.php", send_post, refresh, obj_id, -1)
	}
}

function refresh(request) {
	if (request) window.location.href = "desc.php?obj_id=" + obj_id
}