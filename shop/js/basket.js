var basketZone = document.getElementById('basket');

function getBasketFromLocalStorage () {
	var JSONbasket = localStorage.getItem('basket');
	var basket = JSON.parse(JSONbasket);
	return basket;
}

function setBasketToLocalStorage (basket) {
	var JSONbasket = JSON.stringify(basket);
	localstorage.setItem('basket', JSONbasket); 
}

window.onload = function(e){
	var basket = getBasketFromLocalStorage();
	if (!basket) {
		basket = { };
	}
	console.log('loaded and complete');
	printBasketString(basket);
	setBasketToLocalStorage(basket);
}
function printBasketString(basket){
	var numberOfProducts = basket.length;
	if (!numberOfProducts) { numberOfProducts = 0;}

	basketZone.innerHTML = numberOfProducts
						 + " in basket";
}