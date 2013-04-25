/*
Functions for submitting the new product form
*/

// Get form, button and div for displaying responses
var addProductForm = document.getElementById("product_form");
var addProductButton = document.getElementById("add_product");
var responseArea = document.getElementById('response_area');

// Add relevant event listeners
addProductButton.addEventListener("click", myButtons, false);
addProductForm.addEventListener("submit", submitForm, false);

function myButtons(e) {

	// Get target of click event
	var thisElement = e.target;
	// If it's on the image inside the button,
	// move target to parent element (button itself)
	if (thisElement.tagName === 'IMG') {
		thisElement = e.target.parentElement;
	};
	// If add product button, show/hide add products form
	if(thisElement.id === "add_product"){
		if (addProductForm.className == "empty") {
			addProductForm.classList.remove("empty");
		} else {
			addProductForm.classList.add("empty");
		}
		
	}
}
/*function getClickables(e){
	var clickables = document.getElementsByClassName('clickable');
}*/
function submitForm(e){
	// Stop form submitting by default form action
	if(e.preventDefault()){
		e.preventDefault();
	}
	// Submit the add product request using requeststring from captureForm
	var requestString = captureForm();
	ajaxRequest('POST', '../api/products/', requestString);
}

function captureForm(){
	// Get all relevant input areas on page
	var inputs = document.getElementsByTagName('input');
	var textArea = document.getElementsByTagName('textarea')[0];

	// Get category from the selector box
	var categorySelector = document.getElementById('category');
	var categoryOptions = categorySelector.options;
	var selectedCategory = categoryOptions[categorySelector.selectedIndex].value;

	// Empty string to add name/value pairs to
	var formString = '';

	// Loop through stored inputs and get the 
	// name and value from each, add to string
	looper:
	for (var i = 0; inputs.length; i++) {
		var input = inputs[i];
		if (input.type == 'reset'){ // Stop at reset button
			break looper;
		}
		if (formString !== '') {
			formString += '&';
		}
		formString += input.name + '=' + input.value;
	}

	// Add the textarea
	formString += '&' + textArea.name + '=' + textArea.value;

	// If new category selected, get the value from 
	// new_category textbox and add newcat flag to the string
	// If not, add selected category to the string
	if (selectedCategory == "NEW") {
		var newCategory = document.getElementById('new_category').value;
		formString += '&catname=' + newCategory;

		var newOption = document.createElement('option');
		newOption.value = newCategory;
		newOption.innerHTML = newCategory;
		categorySelector.add(newOption, null);

		formString += '&flag=' + 'newcat';
	} else {
		formString += '&catname=' + selectedCategory;
	}
	
	return formString;
}
/*
Append response text to text between tags of response area div
*/
function displayResponse(msg){
	responseArea.innerHTML += msg;
}


