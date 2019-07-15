/* add click event listener to all buttons (called whenever a new user search is
 * performed)
 */
function refreshUsersButtons()
{
	var buttons = document.querySelectorAll("#users-table tbody tr.data-row button");
	for (var i = 0; i < buttons.length; i++)
		buttons[i].addEventListener("click", performUserAction);
}

/* execute an action (promote/demote/delete) on a user */
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
	openConfirmBox(rowElement, 'Admin_Users', action, userid);
	span.style.display = "inline";
}
