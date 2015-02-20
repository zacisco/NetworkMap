function check_all() {
	equip_check()
	check_input()
}

function equip_check() {
	var ttl = document.getElementById('title')
	var tmp_ttl = document.getElementById('tmp_ttl')
	var ip = document.getElementById('ip')
	var tmp_ip = document.getElementById('tmp_ip')
	var equipment = document.getElementById('equip_type').getElementsByTagName("option")
	var connect = document.getElementById('connect_type').getElementsByTagName("option")
	var web = document.getElementById('web')
	var tmp_web = document.getElementById('tmp_web')
	var ping = document.getElementById('ping')
	var tmp_ping = document.getElementById('tmp_ping')

	for (var i = 0; i < equipment.length; i++) {
		if (equipment[i].selected) {
			reset(ip, connect, web, ping)
			set_options(i, ttl, tmp_ttl, ip, tmp_ip, connect, web, tmp_web, ping, tmp_ping)
		}
	}
}

function reset(ip, connect, web, ping) {
	ip.readOnly = false
	ip.style.background="#FFFFFF"
	connect[0].disabled = false
	connect[0].selected = true
	connect[1].disabled = false
	connect[2].disabled = false
	web.disabled = false
	web.style.background="#FFFFFF"
	ping.disabled = false
	ping.style.background="#FFFFFF"
}

function lock_mc_ob(ip, connect, web, ping) {
	ip.readOnly = true
	ip.style.background="#CCCCCC"
	connect[0].disabled = true
	connect[1].selected = true
	connect[2].disabled = true
	web.checked = false
	web.disabled = true
	web.style.background="#CCCCCC"
	ping.checked = false
	ping.disabled = true
	ping.style.background="#CCCCCC"
}

function lock_pc_pr(connect, web) {
	connect[0].selected = true
	connect[1].disabled = true
	connect[2].disabled = true
	web.checked = false
	web.disabled = true
	web.style.background="#CCCCCC"
}

function set_options(key, ttl, tmp_ttl, ip, tmp_ip, connect, web, tmp_web, ping, tmp_ping) {
	if (key == 0 || key == 1) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		connect[0].selected = true
		connect[2].disabled = true
	} else if (key == 2) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		connect[0].disabled = true
		connect[1].disabled = true
		connect[2].selected = true
	} else if (key == 3) {
		not_restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		lock_mc_ob(ip, connect, web, ping)
	} else if (key == 4) {
		not_restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		lock_mc_ob(ip, connect, web, ping)
	} else if (key == 5) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		lock_pc_pr(connect, web)
	} else if (key == 6) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping)
		lock_pc_pr(connect, web)
	}
}

function restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping) {
	ip.value = tmp_ip.value
	web.checked = tmp_web.checked
	ping.checked = tmp_ping.checked
}

function not_restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web, ping, tmp_ping) {
	tmp_ip.value = ip.value
	tmp_web.checked = web.checked
	ping.checked = tmp_ping.checked
	ip.value = ""
}

function check_input() {
	var ttl = document.getElementById('title')
	var ip = document.getElementById('ip')
	var reg_ttl = /^[А-яёрстуфхцчшщъыьэюя\w- ()+:_,.?]{0,100}$/i
	var reg_ip = /^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/
	var ttl_result = reg_ttl.test(ttl.value)
	var ip_result = reg_ip.test(ip.value)
	var bttn = document.getElementById('button')
	var ttl_img = document.getElementById('img_' + ttl.id)
	var ip_img = document.getElementById('img_' + ip.id)
	if (ttl_result) ttl_img.src = "img/ok.png"
	else ttl_img.src = "img/wrong.png"
	if (ip_result || ip.value == "") ip_img.src = "img/ok.png"
	else ip_img.src = "img/wrong.png"
	if (ttl_result && (ip_result || ip.value == "")) bttn.disabled = 0
	else bttn.disabled = 1
	setTimeout("check_input()", 1)
}