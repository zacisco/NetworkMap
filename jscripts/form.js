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

	for (var i = 0; i < equipment.length; i++) {
		if (equipment[i].selected) {
			reset(ttl, ip, connect, web)
			set_options(i, ttl, tmp_ttl, ip, tmp_ip, connect, web, tmp_web)
		}
	}
}

function reset(ttl, ip, connect, web) {
	ttl.readOnly = false
	ttl.style.background="#FFFFFF"
	ip.readOnly = false
	ip.style.background="#FFFFFF"
	connect[0].disabled = false
	connect[0].selected = true
	connect[1].disabled = false
	connect[2].disabled = false
	web.disabled = false
	web.style.background="#FFFFFF"
}

function lock(ttl, ip, connect, web) {
	ttl.readOnly = true
	ttl.style.background="#CCCCCC"
	ip.readOnly = true
	ip.style.background="#CCCCCC"
	connect[0].disabled = true
	connect[1].selected = true
	connect[2].disabled = true
	web.checked = false
	web.disabled = true
	web.style.background="#CCCCCC"
}

function set_options(key, ttl, tmp_ttl, ip, tmp_ip, connect, web, tmp_web) {
	if (key == 0 || key == 1) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web)
		connect[0].selected = true
		connect[2].disabled = true
	} else if (key == 2) {
		restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web)
		connect[0].disabled = true
		connect[1].disabled = true
		connect[2].selected = true
	} else if (key == 3) {
		not_restore("Media Converter", ttl, tmp_ttl, ip, tmp_ip, web, tmp_web)
		lock(ttl, ip, connect, web)
	} else if (key == 4) {
		not_restore("Optical Box", ttl, tmp_ttl, ip, tmp_ip, web, tmp_web)
		lock(ttl, ip, connect, web)
	}

	//~ if (key == 0 || key == 1) {
		//~ key = 0
	//~ }
	//~ switch (key) {
		//~ case 0: {
			//~ restore(ttl, tmp_ttl, ip, tmp_ip)
			//~ connect[0].selected = true
			//~ connect[2].disabled = true
			//~ break
		//~ }
		//~ case 2: {
			//~ restore(ttl, tmp_ttl, ip, tmp_ip)
			//~ connect[0].disabled = true
			//~ connect[1].disabled = true
			//~ connect[2].selected = true
			//~ break
		//~ }
		//~ case 3: {
			//~ not_restore("Media Converter", ttl, tmp_ttl, ip, tmp_ip)
			//~ lock(ttl, ip, connect, web)
			//~ break
		//~ }
		//~ case 4: {
			//~ not_restore("Optical Box", ttl, tmp_ttl, ip, tmp_ip)
			//~ lock(ttl, ip, connect, web)
			//~ break
		//~ }
	//~ }
}

function restore(ttl, tmp_ttl, ip, tmp_ip, web, tmp_web) {
	if (ttl.value == "Media Converter" || ttl.value == "Optical Box") {
		ttl.value = tmp_ttl.value
		ip.value = tmp_ip.value
		web.checked = tmp_web.checked
	}
}

function not_restore(title, ttl, tmp_ttl, ip, tmp_ip, web, tmp_web) {
	if (ttl.value != "Media Converter" && ttl.value != "Optical Box") {
		tmp_ttl.value = ttl.value
		tmp_ip.value = ip.value
		tmp_web.checked = web.checked
	}
	ttl.value = title
	ip.value = ""
}

function check_input() {
	var ttl = document.getElementById('title')
	var ip = document.getElementById('ip')
	var reg_ttl = /^[А-яёрстуфхцчшщъыьэюя\w- ():_,.?]{0,50}$/i
	var reg_ip = /^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/
	var ttl_result = reg_ttl.test(ttl.value)
	var ip_result = reg_ip.test(ip.value)
	var bttn = document.getElementById('button')
	var ttl_img = document.getElementById('img_' + ttl.id)
	var ip_img = document.getElementById('img_' + ip.id)
	if (ttl_result) {
		ttl_img.src = "img/ok.png"
	} else {
		ttl_img.src = "img/wrong.png"
	}
	if (ip_result || ip.value == "") {
		ip_img.src = "img/ok.png"
	} else {
		ip_img.src = "img/wrong.png"
	}
	if (ttl_result && (ip_result || ip.value == "")) {
		bttn.disabled = 0
	} else {
		bttn.disabled = 1
	}
	setTimeout("check_input()", 1)
}