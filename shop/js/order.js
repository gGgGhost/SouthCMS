var basketContents = document.getElementById('basket_contents');


extraLoadEvent (basketDisplay);

function basketDisplay () {
	var basket = getBasketFromLocalStorage();

	if (basket.length == 0) {
		var info = document.createElement('h3');
		info.innerHTML = "There are currently no products in the basket.";
		basketContents.appendChild(info);
	}
	else {
		var container = document.createElement('div');
		container.innerHTML = getBasketContents(basket);
		basketContents.appendChild(container);

		var orderButton = document.createElement('button');
		orderButton.id = "place_order";
		orderButton.innerHTML = "Place Order";
		basketContents.appendChild(orderButton);

		var clearButton = document.createElement('button');
		clearButton.id = 'clear_basket';
		clearButton.innerHTML = 'Clear Basket';
		basketContents.appendChild(clearButton);

		var button = document.getElementById('place_order');
		button.addEventListener('click', showHideForm, false);

		var button = document.getElementById('clear_basket');
		button.addEventListener('click', clearBasketContents, false);
	}
}

function showHideForm (e) {
	var form = document.getElementById('order_form');
	if (form.className == 'empty') {

	}
}

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

