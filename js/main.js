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
	if (handler === undefined)
		handler = handleAjaxResponse;
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200)
			handler(this);
	}
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(data);
}

function handleAjaxResponse(response)
{
	var data;
	var usingResponseContainer = false;
	try {
		data = JSON.parse(response.responseText);
		if (!data || typeof data !== "object")
			throw "No JSON data";

		if (data.redirect === true)
			location.replace(data.location);
		return;
	} catch (e) {
		var modal = document.getElementById("modal");
		if (modal !== null
			&& window.getComputedStyle(modal, null).display !== "none") {
			modal = document.getElementById("response-container");
			usingResponseContainer = true;
		}

		if (modal === null) {
			showErrorModal("Can not find modal container.");
			return;
		}

		modal.innerHTML = response.responseText;
		if (usingResponseContainer) {
			modal.style.display = "inline-block";
			modal.classList.add("animate");
			return;
		}
		var responseContainer = document.getElementById("response-container");
		if (responseContainer !== null)
			responseContainer.addEventListener("animationend", removeAnimateClass);
		modal.style.display = "block";
		modal.dispatchEvent(new Event("modalopen"));
	}
}

function removeAnimateClass()
{
	this.classList.remove("animate");
}
