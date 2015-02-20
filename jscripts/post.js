/*
Создание XMLHttpRequest-объекта
Возвращает созданный объект или null, если XMLHttpRequest не поддерживается
*/
function createRequestObject() {
	var request = null
	try { request = new ActiveXObject('Msxml2.XMLHTTP')
	} catch (e) {}
	if (!request) {
		try { request = new ActiveXObject('Microsoft.XMLHTTP')
		} catch (e) {}
	}
	if (!request) {
		try { request = new XMLHttpRequest()
		} catch (e) {}
	}
	return request
}

/*
Кодирование данных (простого ассоциативного массива вида { name : value, ...} в
URL-escaped строку (кодировка UTF-8)
*/
function urlEncodeData(data) {
	var query = []
	if (data instanceof Object) {
		for (var k in data) query.push(encodeURIComponent(k)+"="+encodeURIComponent(data[k]))
		return query.join('&')
	} else return encodeURIComponent(data)
}

/*
Выполнение POST-запроса
url - адрес запроса
data - параметры в виде простого ассоциативного массива { name : value, ...}
callback - (не обяз.) callback-функция, которая будет вызвана после выполнения запроса и получения ответа от сервера
*/
function serverRequest(url, data, callback, id, main) {
	var request = createRequestObject()
	if (!request) return false
	request.onreadystatechange = function() {
		if (request.readyState == 4 && callback) {
			var data = eval("("+callback(request)+")")
			if (main > 0) {
				var online = document.getElementsByClassName(id)
				online[0].setAttribute("fill", data.status)
				online[1].setAttribute("fill", data.status)
			} else if (main == 0) {
				var online = document.getElementById(id)
				online.innerHTML = (data.status == "#FF0000")?"<font id=\""+id+"\" color=\""+data.status+"\">Offline</font>":"<font id=\""+id+"\" color=\""+data.status+"\">Online</font>"
			}
		}
	}
	request.open("POST", url, true)
	if (request.setRequestHeader) request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
	//~ request.send(urlEncodeData(data))
	request.send(data)
	return true
}

/*
Функция отладки
Указать в качестве 3-го параметра (callback) функции serverRequest
*/
function answer(request) {
	if (request) return request.responseText
}