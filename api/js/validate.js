/*
Functions for validating data from input forms
by matching strings in input boxes to criteria

2 main functions call the others

if the validation string returns blank, there were no problems

CMS - Add Product Form:
	validateAddProductForm
		validateProductName
		validateCategory 
		validateCurrency
		validateStock

Shop - Order Form:
	validateOrderForm
		validateFullName
		validateAddress
		validatePostcode
		validateEmail
*/
function validateAddProductForm (name, catfield, catoption, cost, price, stock) {
	var validation = "";

	validation += validateProductName(name);
	validation += validateCategory(catfield, catoption);
	validation += validateCurrency(cost, "cost");
	validation += validateCurrency(price, "price");
	validation += validateStock(stock);

	return validation;
}
function validateProductName (field) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You haven't named the product!</p>";
	} 
	else {
		return "";
	}
}
function validateCategory (field, category) {
	if (category == 'NEW') {
		var noSpaces = field.replace(/\s/, "");

		if (noSpaces == "") {
			return "<p>You haven't specified a category!</p>";
		} 
		else {
			return "";
		}
	}
	return "";
}
function validateCurrency (field, name) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You left the " + name + " field empty!</p>";
	} 
	else if (!/^\d+\.?\d{0,2}$/.test(field)) {
		return "<p>Invalid " + name + " format!</p>";
	} 
	else {
		return "";
	}
}
function validateStock (field) {
	var exp = /^[\d]{0,6}$/;
	var noSpaces = field.replace(/\s/, "");
	if (noSpaces == "") {
		return "<p>You left the stock field empty!</p>";
	}
	else if (!exp.test(field)) {
		return "<p>Invalid entry in the stock field.</p>";
	}
	else {
		return "";
	}
}

function validateOrderForm (name, address, postcode, email) {
	var validation = "";

	validation += validateFullName(name);
	validation += validateAddress(address);
	validation += validatePostcode(postcode);
	validation += validateEmail(email);

	return validation;
}

function validateFullName (field) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You haven't entered a name!</p>";
	}
	else if (!/^[a-zA-Z]+\s*[a-zA-Z]*$/.test(field)) {
		return "<p>Invalid characters in name!</p>";
	}
	else {
		return "";
	}
}
function validateAddress (field) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You haven't entered an address!</p>";
	}
	else if (!/^([\w]|[\,]|[\s])+$/.test(field)) {
		return "<p>Invalid characters in address!</p>";
	}
	else {
		return "";
	}
}
function validatePostcode (field) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You haven't entered a postcode!</p>";
	}
	else if (!/^[\w]+\s*[\w]*$/.test(field)) {
		return "<p>Invalid characters in postcode!</p>";
	}
	else {
		return "";
	}
}
function validateEmail (field) {
	var noSpaces = field.replace(/\s/, "");

	if (noSpaces == "") {
		return "<p>You haven't entered an email address!</p>";
	}
	else if (!/^[\w\.\@]*$/.test(field)) {
		return "<p>Invalid characters in email!</p>";
	}
	else {
		return "";
	}
}