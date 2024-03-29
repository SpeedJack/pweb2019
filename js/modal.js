window.addEventListener("click", closeModal);

/* show a modal with an error message (english only) */
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

/* close a modal if the X button is pressed. If data-closeredirect attribute is
 * defined, performs a redirect after the modal is closed
 */
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
		location.assign(redirecturl);
}

/* set the data-closeredirect attribute */
function setModalRedirect(url)
{
	document.getElementById("modal-content").setAttribute("data-closeredirect", url);
}
