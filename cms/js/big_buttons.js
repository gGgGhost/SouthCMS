// Toggle buttons

var functionHeads = document.getElementsByTagName('h2');

for(var i = 0; i < functionHeads.length; i++) {
	var head = functionHeads[i];
	head.addEventListener("click", hsToggle, false);
}

function hsToggle(e) {
	if(e.target.className === "top toggled"){
		e.target.className = 'top';
		hideIt(e);
	}
	else if(e.target.className === "middle toggled"){
		e.target.className = 'middle';
		hideIt(e);
	}
	else if(e.target.className === "bottom toggled"){
		e.target.className = 'bottom';
		hideIt(e);
	}
	else{
		showIt(e);
		e.target.className += ' toggled';
	}
}
function hideIt(e) {
	var thisElement = e.target;
	var goBackUp = false;
	
	for(var i = 0; i < 3; i++){
		var sibling = thisElement.nextElementSibling;

		// Catch link buttons (enclosed in 'a' tags)
		if(sibling.tagName !== 'FIGURE'){
			sibling = sibling.firstElementChild;
			goBackUp = true;
		}
		sibling.className += ' empty';

		if(goBackUp){
			sibling = sibling.parentElement;
			goBackUp = false;
		}
		thisElement = sibling;
	}
}
function showIt(e) {
	// Get element from event
	var thisElement = e.target;
	var goBackUp = false;

	// Iterate through siblings
	for(var i = 0; i < 3; i++){
		var sibling = thisElement.nextElementSibling;
		// Catch link buttons (enclosed in 'a' tags)
		if(sibling.tagName !== 'FIGURE'){
			sibling = sibling.firstElementChild;
			goBackUp = true;
		}

		if(sibling.className.indexOf("option") !== -1){
			sibling.className = "big_button option";
		}
		else{
			sibling.className = "big_button";
		}
		if(goBackUp){
			sibling = sibling.parentElement;
			goBackUp = false;
		}
		thisElement = sibling;
	}
}