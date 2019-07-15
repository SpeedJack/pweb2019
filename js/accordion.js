window.addEventListener("load", openAccordions);

/* open all accordions that the user has already opened; leave closed the others */
function openAccordions()
{
	var accordionButtons = document.getElementsByClassName("accordion");
	for (var i = 0; i < accordionButtons.length; i++) {
		var name = "acc-" + accordionButtons[i].innerHTML;
		var value = localStorage.getItem(name);
			if (value === null) {
				localStorage.setItem(name, "open");
				value = "open";
			}
			if (value === "open")
				toggleAccordion(accordionButtons[i]);
		accordionButtons[i].addEventListener("click", function () { toggleAccordion(this); });
	}
	/* fix some problems when window is resized */
	window.addEventListener("resize", resizeAccordions);
}

/* open/close accordion */
function toggleAccordion(accordion)
{
	var panel = accordion.nextElementSibling;
	var name = "acc-" + accordion.innerHTML;
	accordion.classList.toggle("open");
	if (panel.style.maxHeight) {
		panel.style.maxHeight = null;
		panel.style.opacity = "0";
		panel.style.padding = "0 10px 0 10px";
		localStorage.setItem(name, "closed");
	} else {
		panel.style.padding = "10px";
		panel.style.opacity = "1";
		panel.style.maxHeight = panel.scrollHeight + "px";
		localStorage.setItem(name, "open");
	}
}

/* close and reopen all accordions */
function resizeAccordions()
{
	var panels = document.getElementsByClassName("chall-container");
	for (var i = 0; i < panels.length; i++) {
		var accordionButton = panels[i].previousElementSibling;
		if (accordionButton.classList.contains('open')) {
			toggleAccordion(accordionButton);
			toggleAccordion(accordionButton);
		}
	}
}
