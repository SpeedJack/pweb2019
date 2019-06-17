for (var i = 0; i < document.forms.length; i++)
	if (document.forms[i].hasAttribute("data-actionurl"))
		document.forms[i].addEventListener("submit", sendForm);

window.addEventListener("click", closeModal);

function showErrorModal(message)
{
	var modal = document.getElementById("modal");
	if (modal === null)
		return;
	modal.innerHTML = "<article id=\"modal-content\">" +
		"<section id=\"modal-title\">" +
			"<h2>Error!<span id=\"modal-closebtn\" class=\"close-modal\" title=\"Close\">&times;</span></h2>" +
			"</section>" +
			"<section id=\"modal-body\">" +
				"<p>An error occured. Please, try again.</p>" +
				"<p>Error Message: " + message + "</p>" +
			"</section>" +
		"</article>";
	modal.style.display = "block";
}

function handleAjaxResponse()
{
	if (this.readyState !== 4)
		return;
	switch (this.status) {
	case 200:
		var modal = document.getElementById("modal");
		if (modal !== null
			&& window.getComputedStyle(modal, null).display !== "none")
			modal = modal.getElementById("response-container");
		if (modal === null) {
			showErrorModal("Can not find modal container.");
			break;
		}
		modal.innerHTML = this.responseText;
		modal.style.display = "block";
		break;
	case 301:
	case 302:
	case 307:
	case 308:
		window.location.replace(this.getResponseHeader("Location"));
		break;
	default:
		showErrorModal("Server responded with an invalid status code: " + this.status);
	}
}

function sendForm()
{
	var data = "";
	var xhttp = new XMLHttpRequest();
	var elements = this.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++)
		data += elements[i].name + "=" + encodeURIComponent(elements[i].value) + "&";
	data = data.slice(0, -1);
	xhttp.onreadystatechange = handleAjaxResponse;
	xhttp.open("POST", this.getAttribute("data-actionurl"), true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(data);
}

function closeModal(event)
{
	var content;
	var modal = document.getElementById("modal");
	var closemodal = document.getElementsByClassName("close-modal");
	var close = false;
	var redirecturl = undefined;
	var i;
	for (i = 0; closemodal !== null && i < closemodal.length; i++)
		if (event.target == closemodal[i]) {
			if (event.target.hasAttribute("data-closeredirect"))
				redirecturl = event.target.getAttribute("data-closeredirect");
			close = true;
			break;
		}
	if (event.target != modal && !close)
		return;

	if (redirecturl === undefined) {
		content = document.getElementById("modal-content");
		if (content !== null && content.hasAttribute("data-closeredirect"))
			redirecturl = content.getAttribute("data-closeredirect");
	}

	modal.style.display = "none";
	if (redirecturl !== undefined)
		window.location.replace(redirecturl);
}
