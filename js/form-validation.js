document.querySelector("#register-form input[name=username]").addEventListener("change", validateField);
document.querySelector("#register-form input[name=email]").addEventListener("change", validateField);
document.querySelector("#register-form input[name=password]").addEventListener("change", validatePassword);
document.querySelector("#register-form input[name=password]").addEventListener("keyup", validatePasswordMatch);
document.querySelector("#register-form input[name=password-again]").addEventListener("keyup", validatePasswordMatch);

const ucfirst = (str) => {
	if (typeof str !== "string") return "";
	return str.charAt(0).toUpperCase() + str.slice(1);
}

function validateField()
{
	if (this.value === "")
		return;
	ajaxQuery("index.php?page=AjaxQuery&action=Validate" + ucfirst(this.name),
		this.name + "=" + encodeURIComponent(this.value),
		handleValidationResponse);
}

function handleValidationResponse(response)
{
	var data;
	try {
		data = JSON.parse(response.responseText);
	} catch (e) {
		return;
	}
	var field = document.querySelector("#register-form input[name=" + data.fieldName + "]");
	var validator = document.getElementById("validatorfor-" + data.fieldName);
	if (field === null || validator === null || field.value !== data.value)
		return;
	if (data.valid === true) {
		field.setCustomValidity("");
		validator.style.display = "none";
		return;
	}
	field.setCustomValidity(data.message);
	validator.innerHTML = data.message;
	validator.style.display = "block";
}

function validatePassword()
{
	var validator = document.getElementById("validatorfor-password");
	if (validator === null)
		return;
	if (this.value !== "" && this.value.length < this.getAttribute("minlength"))
		validator.style.display = "block";
	else
		validator.style.display = "none";
}

function validatePasswordMatch()
{
	var passField = document.querySelector("#register-form input[name=password]");
	var againField = document.querySelector("#register-form input[name=password-again]");
	var validator = document.getElementById("validatorfor-password-again");
	if (passField === null || againField === null || validator === null
		|| passField.value === "" || againField.value === "")
		return;
	if (againField.value === passField.value) {
		passField.setCustomValidity("");
		againField.setCustomValidity("");
		validator.style.display = "none";
		return;
	}
	passField.setCustomValidity(validator.innerHTML);
	againField.setCustomValidity(validator.innerHTML);
	validator.style.display = "block";
}
