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
	var span = document.getElementById("confirm-" + action + "-user");
	if (span === null)
		return;
	span.style.display = "inline";
	openConfirmBox(rowElement, 'Admin_Users', action, userid);
}
