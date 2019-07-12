window.addEventListener("load", addButtonActions);

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

function addButtonActions()
{
	var createBtn = document.getElementById("create-challenge");
	if (createBtn !== null)
		createBtn.addEventListener("click", createChallenge);
	var editBtns = document.querySelectorAll("button[id^=edit-chall]");
	for (var i = 0; i < editBtns.length; i++)
		editBtns[i].addEventListener("click", editChallenge);
	var delBtns = document.querySelectorAll("button[id^=delete-chall]");
	for (var i = 0; i < delBtns.length; i++)
		delBtns[i].addEventListener("click", deleteChallenge);
}

function editChallenge()
{
	var cid = this.id.replace("edit-chall-", "");
	ajaxQuery("index.php?page=Admin_Challenges&action=edit", "cid=" + cid);
}

function createChallenge()
{
	ajaxQuery("index.php?page=Admin_Challenges&action=create");
}
