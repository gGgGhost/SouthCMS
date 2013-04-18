var addProductForm = document.getElementById("product_form");
var addProductButton = document.getElementById("add_product");
var responseArea = document.getElementById('phpstuff');

var p_name = document.getElementById('name');

addProductButton.addEventListener("click", myButtons, false);
addProductForm.addEventListener("submit", submitForm, false);

function myButtons(e) {

	// Catch clicks on img inside button
	var thisElement = e.target;

	if (thisElement.tagName === 'IMG') {
		thisElement = e.target.parentElement;
	};

	if(thisElement.id === "add_product"){
		addProductForm.className = "";
	}
}

function submitForm(e){
	if(e.preventDefault()){
		e.preventDefault();
	}
	var requestString = captureForm();
	ajaxRequest('POST', '../api/products/', requestString);
}

function captureForm(){
	var inputs = document.getElementsByTagName('input');
	var textArea = document.getElementsByTagName('textarea')[0];
	var formString = '';
	looper:
	for (var i = 0; inputs.length; i++) {
		var input = inputs[i];
		if (input.type == 'submit'){
			break looper;
		}
		console.log(input.type);
		console.log(input.value);
		if (formString !== '') {
			formString += '&';
		}
		formString += input.name + '=' + input.value;
	}

	formString += '&' + textArea.name + '=' + textArea.value;
	return formString;
}



function ajaxRequest(requestType, requestPath, requestString){

	requestString = requestString || '';

	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && xhr.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				displayResponse(xhr.responseText);
			}
			else{
				displayResponse("Content type of response did not match requested type");
			}

		}
	}
	if (requestString != ''){
		xhr.send(requestString);
		console.log(requestString);
	}
	else{
		console.log('else');
		xhr.send(null);
	}
	
}

function displayResponse(msg){
	responseArea.innerHTML += msg;
}


