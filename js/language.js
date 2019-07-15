window.addEventListener("load", selectActiveFlag);
var svgFlags = document.getElementById("language-selector").children;
for (var i = 0; i < svgFlags.length; i++)
	if (svgFlags[i].id.startsWith("flag-"))
		svgFlags[i].addEventListener("click", setLanguage);

/* highlight the flag of the current language selection */
function selectActiveFlag()
{
	var lang = getCookie("lang");
	if (lang === "")
		return;
	lang = lang.slice(0, 2);
	var flag = document.getElementById("flag-" + lang);
	if (!flag)
		return;
	flag.classList.add("active");
}

/* set a new language and reloads the page */
function setLanguage()
{
	var lang = this.id.replace("flag-", "");
	var url = new URL(location.href);
	console.log(typeof url.searchParams);
	if (url.searchParams !== undefined && typeof url.searchParams === "object") {
		url.searchParams.set("lang", lang);
		location.replace(url.href);
	} else {
		/* if searchParams is not supported by the browser */
		setCookie("lang", lang, Date.now() + 60*60*24*365*10);
		location.reload();
	}
}
