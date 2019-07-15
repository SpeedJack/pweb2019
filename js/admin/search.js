document.getElementById("search-text").addEventListener("keyup", performSearch);

var previousSearchValue = "";

/* perform a user search, by username or email */
function performSearch()
{
	if (this.value.length < 3 || this.value === previousSearchValue)
		return;

	var data = "search-text=" + encodeURIComponent(this.value) + "&";
	var searchByEmail = document.getElementById("search-by-email");
	if (searchByEmail !== null && searchByEmail.checked)
		data += "search-by=email";
	else
		data += "search-by=username";
	previousSearchValue = this.value;
	ajaxQuery("index.php?page=Admin_Ajax&action=SearchUsers", data, false, handleSearchResponse);
}

/* handle the search response (ajax). allowResponseContainer is ignored (modal
 * not used)
 */
function handleSearchResponse(response, allowResponseContainer)
{
	var table = document.querySelector("#users-table tbody");
	if (table === null)
		return;

	table.innerHTML = response.responseText;
	if (typeof refreshUsersButtons === "function")
		refreshUsersButtons();
}
