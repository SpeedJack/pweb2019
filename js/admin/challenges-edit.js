window.addEventListener("load", addDeleteAction);

function deleteChallenge()
{
	var splittedId = this.id.split('-');
	if (splittedId[1] !== 'chall' || splittedId[2] === undefined)
		return;
	closeConfirmBox();
	var action = encodeURIComponent(splittedId[0]);
	var challid = Number(splittedId[2]);
	if (challid === NaN || challid === 0)
		return;
	var rowElement = this.parentElement;
	while (!rowElement.classList.contains("data-row"))
		rowElement = rowElement.parentElement;
	var span = document.getElementById("confirm-" + action + "-chall");
	if (span === null)
		return;
	span.style.display = "inline";
	openConfirmBox(rowElement, 'Admin_Challenges', action, challid);
}

function addDeleteAction()
{
	var buttons = document.querySelectorAll("button[id^=delete-chall]");
	for (var i = 0; i < buttons.length; i++)
		buttons[i].addEventListener("click", deleteChallenge);
}
