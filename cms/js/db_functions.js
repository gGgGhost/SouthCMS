var addProductForm = document.getElementById("product_form");
var addProductButton = document.getElementById("add_product");
var responseArea = document.getElementById('response_area');

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
	var inputs = document.getElementsByTagName('input');
	var textArea = document.getElementsByTagName('textarea')[0];

	var categorySelector = document.getElementById('category');
	var categoryOptions = categorySelector.options;
	var selectedCategory = categoryOptions[categorySelector.selectedIndex].value;

	var formString = '';

	looper:
	for (var i = 0; inputs.length; i++) {
		var input = inputs[i];
		if (input.type == 'reset'){
			break looper;
		}
		if (formString !== '') {
			formString += '&';
		}
		formString += input.name + '=' + input.value;
	}

	formString += '&' + textArea.name + '=' + textArea.value;

	if (selectedCategory == "NEW") {
		var newCategory = document.getElementById('new_category').value;
		formString += '&catname=' + newCategory;
		var newOption = document.createElement('option');
		newOption.value = newCategory;
		newOption.innerHTML = newCategory;
		categorySelector.add(newOption, null);
		formString += '&newcat=' + 'true';
	} else {
		formString += '&catname=' + selectedCategory;
	}
	
	return formString;
}



function ajaxRequest(requestType, requestPath, requestString){

	requestString = requestString || '';

	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && xhr.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				displayResponse(xhr.responseText);
			}
			else{
				displayResponse("Content type of response did not match requested type");
			}
			return "done";
		}

	}
	if (requestString != ''){
		xhr.send(requestString);
	}
	else{
		xhr.send(null);
	}

	
}

function displayResponse(msg){
	responseArea.innerHTML += msg;
}


