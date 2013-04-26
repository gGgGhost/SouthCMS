/*
Functions relating to ajax requests

ajaxRequest
displayResponse

*/
function ajaxRequest(requestType, requestPath, requestString, responseArea, overwrite){

	// By default don't overwrite
	if (typeof(overwrite) == 'undefined') {
		overwrite = false;
	}
	// Default to empty for GET requests (parameters are in path)
	requestString = requestString || '';

	// Generate XMLHttpRequest based on parameters
	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && xhr.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				//write the response text to the designated area
				displayResponse(xhr.responseText, responseArea, overwrite);
			}
			else{
				displayResponse("Content type of response did not match"
								+ " requested type", responseArea, overwrite);
			}
			return "done";
		}

	}
	// Send request
	if (requestString != ''){
		xhr.send(requestString);
	}
	else{
		xhr.send(null);
	}
}
function displayResponse(msg, area, overwrite){
// Append or overwrite response text between tags of response area div
	if (overwrite) {
		area.innerHTML = msg;
	} else {
		area.innerHTML += msg;
	}
	
}
