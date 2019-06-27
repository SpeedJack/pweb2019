window.addEventListener("load", selectActiveFlag);
var svgFlags = document.getElementById("language-selector").children;
for (var i = 0; i < svgFlags.length; i++)
	if (svgFlags[i].id.startsWith("flag-"))
		svgFlags[i].addEventListener("click", setLanguage);

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

function setLanguage()
{
	var lang = this.id.replace("flag-", "");
	var url = new URL(location.href);
	console.log(typeof url.searchParams);
	if (url.searchParams !== undefined && typeof url.searchParams === "object") {
		url.searchParams.set("lang", lang);
		location.replace(url.href);
	} else {
		setCookie("lang", lang, Date.now() + 10*365*24*60*60);
		location.reload();
	}
}
