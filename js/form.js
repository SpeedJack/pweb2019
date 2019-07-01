for (var i = 0; i < document.forms.length; i++)
	if (document.forms[i].hasAttribute("data-actionurl"))
		document.forms[i].addEventListener("submit", sendForm);

document.getElementById("modal").addEventListener("modalopen", addModalForms);

function addModalForms()
{
	var forms = document.getElementsByClassName("modal-form");
	for (var i = 0; i < forms.length; i++) {
		var children = forms[i].children;
		for (var j = 0; j < children.length; j++)
			if (children[j].hasAttribute("autofocus")) {
				children[j].focus();
				break;
			}
		forms[i].addEventListener("submit", sendForm);
	}
}

function sendForm()
{
	var data = "";
	var elements = this.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++)
		data += elements[i].name + "=" + encodeURIComponent(elements[i].value) + "&";
	data = data.slice(0, -1);
	ajaxQuery(this.getAttribute("data-actionurl"), data);
}
