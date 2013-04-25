function ajaxRequest(requestType, requestPath, requestString, responseArea){

	requestString = requestString || '';

	var xhr = new XMLHttpRequest();
	xhr.open(requestType, requestPath);
	xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState === 4 && xhr.status === 200){
			var type = xhr.getResponseHeader("Content-Type");
			if (type.match(/^text/)){
				displayResponse(xhr.responseText, responseArea);
			}
			else{
				displayResponse("Content type of response did not match requested type", responseArea);
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
function displayResponse(msg, area){
	area.innerHTML += msg;
}
