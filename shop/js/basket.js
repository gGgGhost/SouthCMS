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

function clearBasketContents () {
	var basket = [];
	setBasketToLocalStorage(basket);
	printBasketString(basket);
	basketContents.innerHTML = "";
	basketDisplay();
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