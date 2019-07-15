window.addEventListener("load", getNavbarOffsetTop);

var navbarOffsetTop;

/* if the window is scrolled enough, fix the navbar on top */
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

/* get the offsetTop of the navbar */
function getNavbarOffsetTop()
{
	var navbar = document.getElementsByTagName("nav")[0];
	if (navbar === undefined)
		return;
	navbarOffsetTop = navbar.offsetTop;
	window.addEventListener("scroll", stickyNavbar);
}
