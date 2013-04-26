function validateAddProductForm (name, catfield, catoption, cost, price, stock) {
	if (typeof(catfield) == 'undefined') {
		catfield = "";
	}
	var validation = "";
	validation += validateProductName(name);
	validation += validateCategory(catfield, catoption);
	validation += validateCurrency(cost, "cost");
	validation += validateCurrency(price, "price");
	validation += validateStock(stock);
	return validation;
}
function validateCategory (field, category) {
	if (category == 'NEW') {
		var noSpaces = field.replace(/\s/, "");
		if (noSpaces == "") {
			return "<p>You haven't specified a category!</p>";
		}
	}
	else {
		return "";
	}
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