
function alterDB(e) {

	// Catch clicks on img inside button
	var thisElement = e.target;

	if (thisElement.tagName === 'IMG') {
		thisElement = e.target.parentElement;
	};

	if(thisElement.id === "create_database"){
		var option_path = "create_database.php";
	}
	else if(thisElement.id === "destroy_database"){
		var option_path = "destroy_database.php";
	}

	//Create placeholder bar
	writeInfoBar('Connecting to MySQL...', 'neutral');

	// Process request
	var request = new XMLHttpRequest();
	request.open("GET", option_path);
	request.onreadystatechange = function(){
		if (request.readyState === 4 && request.status === 200){
			var type = request.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				writeInfoBar(request.responseText, 'positive');
			}
			else{
				writeInfoBar("Content Type doesn't match that requested", 'negative');
			}

		}
	}
	request.send(null);

}

var destroyButton = document.getElementById("destroy_database");
destroyButton.addEventListener("click", alterDB, false);


var createButton = document.getElementById("create_database");
createButton.addEventListener("click", alterDB, false);
