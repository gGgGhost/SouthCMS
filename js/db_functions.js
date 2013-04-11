
function myEvents(e) {

	// Catch clicks on img inside button
	var thisElement = e.target;

	if (thisElement.tagName === 'IMG') {
		thisElement = e.target.parentElement;
	};

	if(thisElement.id === "add_product"){
		var option_path = "/add";
	}
	else if(thisElement.id === "destroy_database"){
		var option_path = "destroy_database.php";
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

var addProductButton = document.getElementById("add_product");
addProductButton.addEventListener("click", myEvents, false);
