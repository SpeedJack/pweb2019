window.addEventListener("load", function () { drawMenuBars("#BFC2C4"); });
var menuBarsElement = document.getElementById("menu-bars");
menuBarsElement.addEventListener("click", openResponsiveMenu);
menuBarsElement.addEventListener("mouseover", function () { drawMenuBars("#FFFFFF"); });
menuBarsElement.addEventListener("mouseout", function () { drawMenuBars("#BFC2C4"); });


/* draw the three bars symbol used to open menus */
function drawMenuBars(barsColor)
{
	var menuBarsCanvas = document.getElementById("menu-bars");
	if (menuBarsCanvas === null)
		return;
	var canvasContext = menuBarsCanvas.getContext("2d");
	canvasContext.clearRect(0, 0, 25, 20);
	canvasContext.moveTo(0, 1);
	canvasContext.lineTo(25, 1);
	canvasContext.moveTo(0, 10);
	canvasContext.lineTo(25, 10);
	canvasContext.moveTo(0, 19);
	canvasContext.lineTo(25, 19);
	canvasContext.strokeStyle = barsColor;
	canvasContext.stroke();
}

/* open the menu */
function openResponsiveMenu()
{
	var topnav = document.getElementsByTagName("nav")[0];
	if (topnav === undefined)
		return;
	topnav.classList.toggle("open");
	topnav.scrollIntoView();
}
