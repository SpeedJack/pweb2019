document.getElementById("username").addEventListener("change", validateField);
document.getElementById("email").addEventListener("change", validateField);
document.getElementById("password").addEventListener("change", validatePassword);
document.getElementById("password").addEventListener("keyup", validatePasswordMatch);
document.getElementById("password-again").addEventListener("keyup", validatePasswordMatch);

/* make the first letter of a string upercase */
const ucfirst = (str) => {
	if (typeof str !== "string") return "";
	return str.charAt(0).toUpperCase() + str.slice(1);
}

/* check if a field is valid using Ajax */
function validateField()
{
	if (this.value === "")
		return;
	ajaxQuery("index.php?page=Ajax&action=Validate" + ucfirst(this.name),
		this.name + "=" + encodeURIComponent(this.value), false,
		handleValidationResponse);
}

/* handle the validation response from Ajax, showing a message with the error
 * (if any)
 *
 * response: contains the Ajax response
 * allowResponseContainer: ignored (used with modal containers)
 */
function handleValidationResponse(response, allowResponseContainer)
{
	var data;
	try {
		data = JSON.parse(response.responseText);
	} catch (e) {
		return;
	}
	var field = document.getElementById(data.fieldName);
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

/* show an error if the password does not match valid criteria */
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

/* show an error if the two passwords do not match */
function validatePasswordMatch()
{
	var passField = document.getElementById("password");
	var againField = document.getElementById("password-again");
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
