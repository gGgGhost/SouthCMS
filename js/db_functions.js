var addProductForm = document.getElementById("product_form");
var addProductButton = document.getElementById("add_product");

addProductButton.addEventListener("click", myButtons, false);

console.log("using js");

function myButtons(e) {

	// Catch clicks on img inside button
	var thisElement = e.target;
	console.log("clicked");

	if (thisElement.tagName === 'IMG') {
		thisElement = e.target.parentElement;
		console.log("in image");
		console.log(thisElement.id);
	};

	if(thisElement.id === "add_product"){
		console.log("product button");
		addProductForm.className = "";
		console.log("class: " + addProductForm.className);
	}

	// Process request
	

}

function XHRquest(requestType, requestPath){

	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && request.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				writeInfoBar(request.responseText, 'positive');
			}
			else{
				writeInfoBar("Content Type doesn't match that requested", 'negative');
			}

		}
	}
	xhr.send(null);
}


