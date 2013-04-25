var basketZone = document.getElementById('basket_zone');


extraLoadEvent (basketDisplay);

function basketDisplay () {
	var basket = getBasketFromLocalStorage();

	if (basket.length == 0) {
		var info = document.createElement('h3');
		info.innerHTML = "There are currently no products in the basket.";
		basketZone.appendChild(info);
	}
	else {
		var container = document.createElement('div');
		container.id = "basket_contents";
		container.innerHTML = getBasketContents(basket);
		basketZone.appendChild(container);

		var orderButton = document.createElement('button');
		orderButton.id = "place_order";
		orderButton.innerHTML = "Place Order";
		basketZone.appendChild(orderButton);

		var clearButton = document.createElement('button');
		clearButton.id = 'clear_basket';
		clearButton.innerHTML = 'Clear Basket';
		basketZone.appendChild(clearButton);

		var button = document.getElementById('place_order');
		button.addEventListener('click', showHideForm, false);

		var button = document.getElementById('clear_basket');
		button.addEventListener('click', clearBasketContents, false);

	}
}

function showHideForm (e) {
	var form = document.getElementById('order_form');
	if (form.className == 'empty') {
		form.classList.remove('empty');
	}
	else {
		form.classList.add('empty');
	}
}
// Add an extra event to window.onload
// if it already has an assigned function
function extraLoadEvent (func) {
	existingOnload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	}
	else {
		window.onload = function () {
			if (existingOnload) {
				existingOnload();
			}
			func();
		}
	}
}

