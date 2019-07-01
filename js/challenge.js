var challenges = document.getElementsByClassName("chall");
for (var i = 0; i < challenges.length; i++)
	if (!challenges[i].classList.contains("solved-chall"))
		challenges[i].addEventListener("click", openChallenge);

function openChallenge()
{
	var cid = this.id.replace("chall-", "");
	ajaxQuery("index.php?page=challenges&action=open", "cid=" + cid)
}
