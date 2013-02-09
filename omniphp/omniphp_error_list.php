<?php

/**
 * The "macros" for the Errors.
 * 
 * @todo add documentation
 */

/*
default jquery.validate.js values:

	messages: {
		required: "This field is required.",
		remote: "Please fix this field.",
		email: "Please enter a valid email address.",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date (ISO).",
		number: "Please enter a valid number.",
		digits: "Please enter only digits.",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please enter the same value again.",
		maxlength: $.validator.format("Please enter no more than {0} characters."),
		minlength: $.validator.format("Please enter at least {0} characters."),
		rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
		range: $.validator.format("Please enter a value between {0} and {1}."),
		max: $.validator.format("Please enter a value less than or equal to {0}."),
		min: $.validator.format("Please enter a value greater than or equal to {0}.")
	},
	
to add: additional-methods.js

	...

*/

define("OMNIPHP_PREFIX_ERRORS", true); //prefixes all errors with "Error: "
if(defined("OMNIPHP_PREFIX_ERRORS"))
{
	define("OMNIPHP_ERROR_PREFIX", "* Error: ");
}
else
{
	define("OMNIPHP_ERROR_PREFIX", "");
}

//Properties:
define("OMNIPHP_ERROR_REQUIRED", "[name] is required.");
define("OMNIPHP_ERROR_MIN", "The value [value] in [name] is less than [min].");
define("OMNIPHP_ERROR_MAX", "The value [value] in [name] is greater than [max].");
define("OMNIPHP_ERROR_MINLENGTH", "[name] must consist of at least [minlength] characters.");
define("OMNIPHP_ERROR_MAXLENGTH", "[name] must not exceed [maxlength] characters.");

//Properties - Types:
define("OMNIPHP_ERROR_PHONE", "[name] is incorrect, please type a correct phone number.");
define("OMNIPHP_ERROR_EMAIL", "[name] is incorrect, please type a correct email address.");
define("OMNIPHP_ERROR_CREDITCARD", "[name] is incorrect, please type a correct email address.");

/*
 CellPhone: {
required: "Cell Phone Required",
phoneUS: "Enter phone in correct format",
minlength: "Your phone must consist of at least 12 characters",
maxlength: "Your phone cannot exceed 12 characters"
},
HomePhone: {
required: "Home Phone Required",
phoneUS: "Enter Home Phone in correct format",
minlength: "Your Home Phone must consist of at least 12 characters",
maxlength: "Your Home Phone cannot exceed 12 characters"
},
Text1: {
required: "Text1 Required"
}
Email: {
required: "Email Required",
email: "Please enter a valid email address (i.e. user@domain.???)",
minlength: "Your phone must consist of at least 12 characters",
maxlength: "Your phone cannot exceed 12 characters"
}
*/
?>
