function setCookie(name, value, expire)
{
	var exp = new Date();
	exp.setTime(expire);
	document.cookie = name + "=" + value + ";expires=" + exp.toUTCString() + ";path=/";
}

function getCookie(name)
{
	var cookies = decodeURIComponent(document.cookie).split(";");
	name = name + "=";
	for (var i = 0; i < cookies.length; i++) {
		var cookie = cookies[i];
		while (cookie.charAt(0) === " ")
			cookie = cookie.substring(1);
		if (cookie.indexOf(name) === 0)
			return cookie.substring(name.length, cookie.length);
	}
	return "";
}

function ajaxQuery(url, data, handler)
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200)
			handler(this);
	}
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(data);
}
