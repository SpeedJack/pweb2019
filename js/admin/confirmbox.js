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

function confirmUserAction(page, action, id)
{
	ajaxQuery("index.php?page=" + page + "&action=" + action, "id=" + id, false, function () { location.reload() });
}

function closeConfirmBox()
{
	var confirmboxDivs = document.querySelectorAll("tr.confirmbox td div");
	for (var i = 0; i < confirmboxDivs.length; i++)
		confirmboxDivs[i].classList.remove("open");
}
