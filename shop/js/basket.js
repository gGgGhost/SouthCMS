var basketHeader = document.getElementById('basket');
var name_elem = document.getElementById('product_name');
var num_elem = document.getElementById('product_prodnum');
var price_elem = document.getElementById('product_price');
var orderForm = document.getElementById('order_form');
if (name_elem && num_elem && price_elem) {
	var productName = name_elem.innerHTML;
	var productId = num_elem.innerHTML;
	var price = price_elem.innerHTML;
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
	printBasketString(basket); // Print the new empty basket to screen
	basketZone.innerHTML = ""; // Clear the basket contents list
	basketDisplay(); // Reprint basket contents screen (telling basket is empty)
	if (orderForm.class != 'empty') {
		orderForm.classList.add('empty');
		basketZone.innerHTML = basketZone.innerHTML;
	}
}
function getBasketContents (basket) {
	var numberOfLines = basket.length;
	var contents = "<table>";
	var totalPrice = 0;

	for (var i = 0; i < numberOfLines; i++) {
		var thisProduct = basket[i];
		var name = thisProduct.productName;
		var quantity = thisProduct.quantity;
		var id = thisProduct.productId;
		var price = thisProduct.price;
		var price = Number(price).toFixed(2);
		var line = "<tr><td><a href='../products/?id=" + id + "'>";
		line += name + "</a></td><td>x" + quantity + "</td><td>&pound;" + price + "</td></tr>";
		contents += line;
		totalPrice += Number(price);
	}
	contents += "<tr><td>Total price:</td><td></td><td>&pound;<span id='total_price'>" + 
				totalPrice.toFixed(2) + "</span></td></tr></table>";
	return contents;
}