
function createDB(e) {
	var request = new XMLHttpRequest();
	request.open("GET", "create_database.php");
	request.onreadystatechange = function(){
		if (request.readyState === 4 && request.status === 200){
			var type = request.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				writeInfoBar(request.responseText, 'neutral');
			}
			else{
				console.log('not matching type');
			}

		}
	}
	request.send(null);
}
function destroyDB(e){
	var request = new XMLHttpRequest();
	request.open("GET", "drop_database.php");
	request.onreadystatechange = function(){
		if (request.readyState === 4 && request.status === 200){
			var type = request.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				writeInfoBar(request.responseText, 'neutral');
			}
			else{
				console.log('not matching type');
			}

		}
	}
	request.send(null);
}
var destroyButton = document.getElementById("destroy_database");
destroyButton.addEventListener("click", destroyDB, false);


var createButton = document.getElementById("create_database");
createButton.addEventListener("click", createDB, false);


// Info bar functions

