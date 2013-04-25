var basketHeader = document.getElementById('basket');
var product = document.getElementById('product_name');
var prodnum = document.getElementById('product_prodnum');
if (product && prodnum) {
	var productName = product.innerHTML;
	var productId = prodnum.innerHTML;
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

	if (productId) {
		var search = checkBasketForProduct(basket, productId);
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
	basketHeader.innerHTML = numberOfProducts
						 + message;
}

/*
Delete all products from the basket
*/
function clearBasketContents () {
	var basket = []; // basket is empty array
	setBasketToLocalStorage(basket); // Write to localStorage
	printBasketString(basket); // Print the new empty basket to screem
	basketZone.innerHTML = ""; // Clear the basket contents list
	basketDisplay(); // Reprint basket contents screen (telling basket is empty)
}

function getBasketContents (basket) {
	var numberOfLines = basket.length;
	var contents = "";

	for (var i = 0; i < numberOfLines; i++) {
		var thisProduct = basket[i];
		var name = thisProduct.productName;
		var quantity = thisProduct.quantity;
		var id = thisProduct.productId;
		var line = "<p><a href='../products/?id=" + id + "'>";
		line += name + "</a> x" + quantity + "</p>";
		contents = contents + line;
	}
	return contents;
}