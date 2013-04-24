var basketZone = document.getElementById('basket');
var product = document.getElementById('product_name');
if (product) {
	var productName = product.innerHTML;
}

function getBasketFromLocalStorage () {
	var JSONbasket = localStorage.getItem('basket');
	var basket = JSON.parse(JSONbasket);
	return basket;
}

function setBasketToLocalStorage (basket) {
	var JSONbasket = JSON.stringify(basket);
	localStorage.setItem('basket', JSONbasket); 
}

window.onload = function(e){
	var basket = getBasketFromLocalStorage();
	if (!basket) {
		basket = [];
	}
	printBasketString(basket);
	setBasketToLocalStorage(basket);

	if (productName) {
		var search = checkBasketForProduct(basket, productName);
		if (search != "not found") {
			updateScreen(basket[search].quantity);
		}
	}
}
function printBasketString(basket){
	var numberOfProducts = basket.length;
	if (!numberOfProducts) { numberOfProducts = 0;}
	switch (numberOfProducts) {
		case 1: 	
				var message = " product in basket";
				break;
		default:
				var message = " products in basket";
	}
	basketZone.innerHTML = numberOfProducts
						 + message;
}

/*
Delete all products from the basket
*/
function clearBasketContents () {
	var basket = []; // basket is empty array
	setBasketToLocalStorage(basket); // Write to localStorage
	printBasketString(basket); // Print the new empty basket to screem
	basketContents.innerHTML = ""; // Clear the basket contents list
	basketDisplay(); // Reprint basket contents screen (telling basket is empty)
}

function getBasketContents (basket) {
	var numberOfLines = basket.length;
	var contents = "";

	for (var i = 0; i < numberOfLines; i++) {
		var thisProduct = basket[i];
		var name = thisProduct.productName;
		var quantity = thisProduct.quantity;
		var line = "<p>" + name + " x" + quantity + "</p>";
		contents = contents + line;
	}
	return contents;
}