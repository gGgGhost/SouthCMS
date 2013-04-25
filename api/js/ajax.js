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