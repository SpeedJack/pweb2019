for (var i = 0; i < document.forms.length; i++)
	if (document.forms[i].hasAttribute("data-actionurl"))
		document.forms[i].addEventListener("submit", sendForm);

function handleFormResponse(response)
{
	var data;
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
			&& window.getComputedStyle(modal, null).display !== "none")
			modal = modal.getElementById("response-container");

		if (modal === null) {
			showErrorModal("Can not find modal container.");
			return;
		}
		modal.innerHTML = response.responseText;
		modal.style.display = "block";
	}

}

function sendForm()
{
	var data = "";
	var elements = this.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++)
		data += elements[i].name + "=" + encodeURIComponent(elements[i].value) + "&";
	data = data.slice(0, -1);
	ajaxQuery(this.getAttribute("data-actionurl"), data, handleFormResponse);
}
