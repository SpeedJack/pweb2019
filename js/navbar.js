window.addEventListener("load", getNavbarOffsetTop);

var navbarOffsetTop;

function stickyNavbar()
{
	var navbar = document.getElementsByTagName("nav")[0];
	if (navbar === undefined)
		return;
	if (window.pageYOffset >= navbarOffsetTop)
		navbar.classList.add("sticky");
	else
		navbar.classList.remove("sticky");
}

function getNavbarOffsetTop()
{
	var navbar = document.getElementsByTagName("nav")[0];
	if (navbar === undefined)
		return;
	navbarOffsetTop = navbar.offsetTop;
	window.addEventListener("scroll", stickyNavbar);
}
