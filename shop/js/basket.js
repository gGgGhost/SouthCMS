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
	console.log('loaded and complete');
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