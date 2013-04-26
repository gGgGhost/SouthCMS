function ajaxRequest(requestType, requestPath, requestString, responseArea, overwrite){
	if (typeof(overwrite) == 'undefined') {
		overwrite = false;
	}
	requestString = requestString || '';

	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && xhr.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				displayResponse(xhr.responseText, responseArea, overwrite);
			}
			else{
				displayResponse("Content type of response did not match"
								+ " requested type", responseArea, overwrite);
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

/*
Append response text to text between tags of response area div
*/
function displayResponse(msg, area, overwrite){
	if (overwrite) {
		area.innerHTML = msg;
	} else {
		area.innerHTML += msg;
	}
	
}
