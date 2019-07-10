function refreshUsersButtons()
{
	var buttons = document.querySelectorAll("#users-table tbody tr.data-row button");
	for (var i = 0; i < buttons.length; i++)
		buttons[i].addEventListener("click", performUserAction);
}

function performUserAction()
{
	var splittedId = this.id.split("-");
	if (splittedId[1] !== "user" || splittedId[2] === undefined)
		return;
	closeConfirmBox();
	var action = encodeURIComponent(splittedId[0]);
	var userid = Number(splittedId[2]);
	if (userid === NaN || userid === 0)
		return;
	var rowElement = this.parentElement;
	while (!rowElement.classList.contains("data-row"))
		rowElement = rowElement.parentElement;
	var confirmbox = document.querySelector("tr.confirmbox");
	if (confirmbox === null)
		return;
	rowElement.after(confirmbox);
	var span = document.getElementById("confirm-" + action + "-user");
	if (span === null)
		return;
	var yesButton = document.getElementById("confirmbox-yes");
	var noButton = document.getElementById("confirmbox-no");
	if (yesButton === null || noButton === null)
		return;
	yesButton.onclick = function() { confirmUserAction(action, userid); }
	noButton.onclick = closeConfirmBox;
	span.style.display = "inline";
	var confirmboxDivs = document.querySelectorAll("tr.confirmbox td div");
	for (var i = 0; i < confirmboxDivs.length; i++) {
		confirmboxDivs[i].style.padding = "20px 0";
		confirmboxDivs[i].style.maxHeight = "40px";
	}
}

function confirmUserAction(action, userid)
{
	ajaxQuery("index.php?page=Admin_Users&action=" + action, "userid=" + userid, false, function () { location.reload() });
}

function closeConfirmBox()
{
	var confirmboxDivs = document.querySelectorAll("tr.confirmbox td div");
	for (var i = 0; i < confirmboxDivs.length; i++) {
		confirmboxDivs[i].style.maxHeight = null;
		confirmboxDivs[i].style.padding = null;
	}
	var spans = document.querySelectorAll("table tr.confirmbox td div span[id^=confirm-]");
	for (var i = 0; i < spans.length; i++)
		spans[i].style.display = null;
}
