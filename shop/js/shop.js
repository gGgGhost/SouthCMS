var addToBasketButton = document.getElementById('buy');
var added = document.getElementById('added');


addToBasketButton.addEventListener("click", addToBasket, false);

function addToBasket (e) {
	var basket = getBasketFromLocalStorage();
	var basketCounter = basket.length;

	if (!basketCounter) {
		basketCounter = 0;
	}

	var search = checkBasketForProduct(basket, productName);

	if (search != "not found") {
		var thisProduct = basket[search];
		basketCounter = search;
	}

	if (!thisProduct) {
		var thisProduct = {};
		thisProduct.productName = productName;
		thisProduct.quantity = 1;
	}
	else{
		thisProduct.quantity++;
	}
	
	basket[basketCounter] = thisProduct;
	setBasketToLocalStorage(basket);
	updateScreen(thisProduct.quantity);
	printBasketString(basket);
}

function updateScreen(quantity) {
	added.innerHTML = quantity + " in basket";
}

function checkBasketForProduct(basket, product){
	var basketCounter = basket.length;

	for (var i = 0; i < basketCounter; i++) {
		if (basket[i].productName == product) {
			return i;
		}
	}
	return "not found";
}