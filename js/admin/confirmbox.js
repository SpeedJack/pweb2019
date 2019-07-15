/* open a confirm box (question with yes/no)
 * row: the table's row that triggered this function
 * page: the page to send the request if the user presses yes
 * action: the action to send the request if the user presses yes
 * id: the id of the entity to work on
 */
function openConfirmBox(row, page, action, id)
{
	var confirmbox = document.querySelector("tr.confirmbox");
	if (confirmbox === null)
		return;
	row.after(confirmbox);
	var yesButton = document.getElementById("confirmbox-yes");
	var noButton = document.getElementById("confirmbox-no");
	if (yesButton === null || noButton === null)
		return;
	yesButton.onclick = function() { confirmUserAction(page, action, id); }
	noButton.onclick = closeConfirmBox;
	var spans = document.querySelectorAll("table tr.confirmbox td div span[id^=confirm-]");
	for (var i = 0; i < spans.length; i++)
		spans[i].style.display = null;
	var confirmboxDivs = document.querySelectorAll("tr.confirmbox td div");
	for (var i = 0; i < confirmboxDivs.length; i++)
		confirmboxDivs[i].classList.add("open");
}

/* send ajax request to the specified page and action passing the specified id */
function confirmUserAction(page, action, id)
{
	ajaxQuery("index.php?page=" + page + "&action=" + action, "id=" + id, false, function () { location.reload() });
}

/* close a confirm box */
function closeConfirmBox()
{
	var confirmboxDivs = document.querySelectorAll("tr.confirmbox td div");
	for (var i = 0; i < confirmboxDivs.length; i++)
		confirmboxDivs[i].classList.remove("open");
}
