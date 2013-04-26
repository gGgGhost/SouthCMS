var basketZone = document.getElementById('basket_zone');
var orderButton = document.getElementById('confirm_order');
var responseArea = document.getElementById('response_area');


orderButton.addEventListener('click', submitForm, false);

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

		var buttons = document.createElement('p');
		basketZone.appendChild(buttons);

		var orderButton = document.createElement('button');
		orderButton.id = "place_order";
		orderButton.innerHTML = "Place Order";
		buttons.appendChild(orderButton);

		var clearButton = document.createElement('button');
		clearButton.id = 'clear_basket';
		clearButton.innerHTML = 'Clear Basket';
		buttons.appendChild(clearButton);

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

function captureForm(){
	// Get all relevant input areas on page
	var inputs = document.getElementsByTagName('input');

	// Empty string to add name/value pairs to
	var formString = '';

	// Loop through stored inputs and get the 
	// name and value from each, add to string
	looper:
	for (var i = 0; i < inputs.length; i++) {
		var input = inputs[i];
		if (input.type != 'text'){ // Stop at reset button
			continue looper;
		}
		if (formString !== '') {
			formString += '&';
		}
		formString += input.name + '=' + input.value;
	}
	var valid = validateOrderForm (inputs[2].value, inputs[3].value, inputs[4].value, inputs[5].value);
	if (valid != "") {
		responseArea.innerHTML = valid;
		formString = "";
	}
	return formString;
}

function submitForm(e){
	var basket = getBasketFromLocalStorage();
	var order = JSON.stringify(basket);
	// Stop form submitting by default form action
	if(e.preventDefault()){
		e.preventDefault();
	}
	// Submit the add product request using requeststring from captureForm
	var requestString = captureForm();
	if (requestString != '') {
		requestString += "&basket=" + order;
		ajaxRequest('POST', '../../api/orders/', requestString, basketZone);
		clearBasketContents("<h2>Order Submitted</h2>");
	}
	
}