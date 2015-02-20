var doc_width = document.documentElement.clientWidth
var doc_height = document.documentElement.clientHeight
var new_width = 0, new_height = 0
var x = 0, y = 0, all_x = 0, all_y = 0
//~ var factor = 10
var tmp_ip = ""
var fst_clk = true

/*
Автоматическое выполнение кода при загрузке страницы
*/
if (window.addEventListener) { // Mozilla
	window.addEventListener('load', addEvents, false)
}
//~ else if (window.attachEvent) { // Other Browser
	//~ window.attachEvent('onload', addEvents)
//~ }

function load() {
	var TEXTs = document.getElementsByTagName("text")
	for (var i = 0; i < TEXTs.length; i++) {
		var ip = TEXTs[i].getAttribute('class')
		var reg_ip = /^(([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])\.)(((\d|[1-9]\d|1\d{2}|2[0-4]\d|25[0-5]))\.){2}([1-9]\d{0,1}|1\d{2}|2[0-4]\d|25[0-4])$/
		var ip_result = reg_ip.test(ip)
		if (ip_result) {
			if (tmp_ip == ip) tmp_ip = ""
			else {
				tmp_ip = ip
				var send_post = "ip="+ip
				serverRequest("ping.php", send_post, answer, ip, 1)
			}
		}
	}
}

/*
Присваение элементам <img> обработку событий:
onMouseDown функцией move
и onClick функцией control
Сделано для уменьшения кода страницы, прописывается виртуально
*/
function addEvents() {
	var view_obj = document.getElementById('background')
	view_obj.addEventListener('mousedown', move_equip, false)
	var img_list = document.getElementsByTagName('image')
	var reg = /^[1-9]\d{0,10}$/
	for (var i = 0; i < img_list.length; i++) {
		if (reg.test(img_list[i].getAttribute('id'))) img_list[i].addEventListener('mousedown', move_equip, false)
		else img_list[i].addEventListener('click', control_equip, false)
	}
}

/*
Функция перечещения объектов по событию onMouseDown
*/
function move_equip(evt) {
	var left, top
	var obj = evt.target
	if (obj.getAttribute('id') == "background") {
		obj.clicked_view = true
		if (fst_clk) {
			fst_clk = false
			check_box()
		}
	} else obj.clicked = true
	obj.mousePosX = parseInt(evt.clientX)
	obj.mousePosY = parseInt(evt.clientY)
	if (evt.preventDefault) evt.preventDefault()
	else evt.returnValue = false
	document.onmouseup = function(evt) {
		if (evt.target.getAttribute('id') == "background") {
			obj.clicked_view = false
			check_box()
		} else {//if (!isNaN(evt.target.getAttribute('id'))) {
			obj.clicked = false
			if (!isNaN(left) && !isNaN(top)) {
				var send_post = "obj_id=" + obj.getAttribute('id') +"&posX=" + left + "&posY=" + top
				serverRequest("save_pos.php", send_post)
			}
		}
	}
	document.onmousemove = function(evt) {
		if (obj.clicked_view) {
			var view_obj = document.getElementById('box')
			var mousePosX = parseInt(evt.clientX)
			var mousePosY = parseInt(evt.clientY)
			//~ window.alert(dx + "|" + dy)
			//~ window.alert(doc_width + "-|-" + doc_height)
			x = all_x + mousePosX - obj.mousePosX
			y = all_y + mousePosY - obj.mousePosY
			var pos = x + ", " + y
			view_obj.setAttribute('transform', "translate(" + pos + ")")
		} else if (obj.clicked) {
			var ex = parseInt(obj.getAttribute('x'))
			var ey = parseInt(obj.getAttribute('y'))
			var mousePosX = parseInt(evt.clientX)
			var mousePosY = parseInt(evt.clientY)
			left = ex + mousePosX - obj.mousePosX
			top = ey + mousePosY - obj.mousePosY
			//if (left > 20 && top > 20) {
				var width = parseInt(obj.getAttribute('width'))
				var height = parseInt(obj.getAttribute('height'))
				var lnk_list = document.getElementsByTagName('line')
				var lnk_pos = [width/2 + left, height/2 + top]
				for (var i = 0; i < lnk_list.length ; i++) {
					var lnk_id = lnk_list[i].getAttribute('id').split(/-/)
					if (lnk_id[0] == obj.getAttribute('id')) {
						lnk_list[i].setAttribute('x1', lnk_pos[0])
						lnk_list[i].setAttribute('y1', lnk_pos[1])
					} else if (lnk_id[1] == obj.getAttribute('id')) {
						lnk_list[i].setAttribute('x2', lnk_pos[0])
						lnk_list[i].setAttribute('y2', lnk_pos[1])
					}
				}
				var txt_list = document.getElementsByTagName('text')
				var ttl_pos = [width/2 + left, top]
				//var dsc_pos = [width/2 + left, height + 10 + top]
				var url_pos = [width/2 + left, height + 10 + top]
				for (var i = 0; i < txt_list.length ; i++) {
					var txt_id = txt_list[i].getAttribute('id').split(/-/)
					if (txt_id[1] == obj.getAttribute('id')) {
						if (txt_id[0] == "title") {
							txt_list[i].setAttribute('x', ttl_pos[0])
							txt_list[i].setAttribute('y', ttl_pos[1])
						} else if (txt_id[0] == "url") {
							txt_list[i].setAttribute('x', url_pos[0])
							txt_list[i].setAttribute('y', url_pos[1])
						}// else if (txt_id[0] == "desc") {
						//	txt_list[i].setAttribute('x', dsc_pos[0])
						//	txt_list[i].setAttribute('y', dsc_pos[1])
						//}
					}
				}
				var img_list = document.getElementsByTagName('image')
				var add_pos = [width + left, top]
				var edit_pos = [width + left, height/3 + top]
				var del_pos = [width + left, height*2/3 + top]
				for (var i = 0; i < img_list.length; i++) {
					var img_id = img_list[i].getAttribute('id').split(/-/)
					if (img_id[1] == obj.getAttribute('id')) {
						if (img_id[0] == "add") {
							img_list[i].setAttribute('x', add_pos[0])
							img_list[i].setAttribute('y', add_pos[1])
						} else if (img_id[0] == "edit") {
							img_list[i].setAttribute('x', edit_pos[0])
							img_list[i].setAttribute('y', edit_pos[1])
						} else if (img_id[0] == "del") {
							img_list[i].setAttribute('x', del_pos[0])
							img_list[i].setAttribute('y', del_pos[1])
						}
					}
				}
				obj.setAttribute('x', left)
				obj.setAttribute('y', top)
				obj.mousePosX = mousePosX
				obj.mousePosY = mousePosY
			//}
		}
	}
}

/*
Функция изменения объектов по событию onClick
*/
function control_equip(evt) {
	var img_id = evt.target.getAttribute('id').split(/-/)
	if (img_id[0] == "del") {
		if (window.confirm('Are you sure you want to completely remove THIS equipment\nand ALL equipments depends on it?')) {
			var send_post = "obj_id=" + img_id[1]
			serverRequest("del.php", send_post, refresh)
		}
	} else if (img_id[0] == "add") window.location.href ="form.php?add&obj_id=" + img_id[1]
	else if (img_id[0] == "edit") window.location.href = "form.php?edit&obj_id=" + img_id[1]
}

function refresh(request) {
	if (request) window.location.reload()
}

function check_box() {
	var view_obj = document.getElementById('box')
	var obj_tr = view_obj.getAttribute('transform')
	if (obj_tr != null) {
		var reg = /translate\(([-\d\.]+),?\s*([-\d\.]*?)\)/i
		all_x = parseInt(reg.exec(obj_tr)[1])
		all_y = parseInt(reg.exec(obj_tr)[2])
	}
}

//~ document.onmousedown = function(evt) {
	
	//~ var obj = evt
	//~ if (evt.target.getAttribute('id') == "background") {
		//~ var obj = document.getElementById('box')
		//~ window.alert(obj.getAttribute('id'))
	//~ }
//~ }

//~ document.onkeypress = view
//~ function () {
	//~ var event = (event) ? event : window.event
	//~ window.alert(event)
	//~ window.alert(String.fromCharCode(event.keyCode))
	//~ window.alert(event.keyCode)
	
//~ }