/* set a cookie */
function setCookie(name, value, expire)
{
	var exp = new Date();
	exp.setTime(expire);
	document.cookie = name + "=" + value + ";expires=" + exp.toUTCString() + ";path=/";
}

/* get the value of a cookie */
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

/* send an asyncronous XMLHttpRequest */
function sendRequest(url, data, xhttp)
{
	if (xhttp === undefined)
		xhttp = new XMLHttpRequest();
	xhttp.open("POST", url, true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(data);
}

/* send an ajax request
 * 
 * url: the url where to send the request
 * data: the url-encoded string of POST parameters
 * allowResponseContainer: passed to the response handler; if true and a modal
 * is already open, the handler should append the response to the element with
 * id response-container instead of replace the content of the modal
 * handler: the handler function (default: handleAjaxResponse)
 */
function ajaxQuery(url, data, allowResponseContainer, handler)
{
	var xhttp = new XMLHttpRequest();
	if (handler === undefined)
		handler = handleAjaxResponse;
	xhttp.onreadystatechange = function() {
		if (this.readyState === 4 && this.status === 200)
			handler(this, (allowResponseContainer === true));
	}
	sendRequest(url, data, xhttp);
}

/* handle an Ajax response, opening the modal container or, if already open and
 * allowResponseContainer=true, appending the response to the element with id
 * response-container
 * If a JSON response is received, performs a redirect
 */
function handleAjaxResponse(response, allowResponseContainer)
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
		if (allowResponseContainer && modal !== null
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

/* when the response-container animation (highlighting) ends, remove the
 * animate class
 */
function removeAnimateClass()
{
	this.classList.remove("animate");
}
