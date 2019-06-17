window.addEventListener("scroll", stickyNavbar);

var navbarOffsetTop = document.getElementsByTagName("nav")[0].offsetTop;

function stickyNavbar()
{
	var navbar = document.getElementsByTagName("nav")[0];
	var offset = navbar.offsetTop;
	if (offset > navbarOffsetTop)
		navbarOffsetTop = offset;
	if (window.pageYOffset >= navbarOffsetTop)
		navbar.classList.add("sticky");
	else
		navbar.classList.remove("sticky");
}
