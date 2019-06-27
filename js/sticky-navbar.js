window.addEventListener("load", getNavbarOffsetTop);

var navbarOffsetTop;

function stickyNavbar()
{
	var navbar = document.getElementsByTagName("nav")[0];
	if (window.pageYOffset >= navbarOffsetTop)
		navbar.classList.add("sticky");
	else
		navbar.classList.remove("sticky");
}

function getNavbarOffsetTop()
{
	navbarOffsetTop = document.getElementsByTagName("nav")[0].offsetTop;
	window.addEventListener("scroll", stickyNavbar);
}
