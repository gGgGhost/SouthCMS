function createDB(e) {
	var request = new XMLHttpRequest();
	request.open("GET", "create_database.php");
	request.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
	request.send(null);
	writeInfoBar('ok', 'neutral');
}


var thisButton = document.getElementById("create_database");
thisButton.addEventListener("click", createDB, false);

// Visual page stuff

var functionHeads = document.getElementsByTagName('h2');

for(var i = 0; i < functionHeads.length; i++) {
	var head = functionHeads[i];
	head.addEventListener("click", hstoggle, false);
}

function hstoggle(e) {
	if(e.target.className == 'toggled') {
		hidr(e);
		e.target.className = '';
	}
	else{
		console.log('soon to showr');
		showr(e);
		e.target.className = 'toggled';
	}
	console.log('toggled');
}
function hidr(e) {
	thisElement = e.target;
	for(var i = 0; i < 2; i++){
		var sibling = thisElement.nextElementSibling;
		sibling.className += ' empty';
		thisElement = sibling;
	}
}
function showr(e) {
	thisElement = e.target;
	for(var i = 0; i < 2; i++){
		console.log('turn');
		var sibling = thisElement.nextElementSibling;
		if(sibling){
			console.log('good');
		}
		else{
			console.log('bad');
		}
		//var nameString = sibling.className;
		//nameString.replace("shit", "empty");
		//console.log(nameString);
		sibling.className = "big_button option";
		thisElement = sibling;
	}
}

// Info bar functions

function barMaker(e) {
	var content = 'writeInfoBar is working like a dream!';
	writeInfoBar(content, e.target.id);
}

function barDestroyer(e) {
	var bar = getBar();
	bar.innerHTML = '';
	bar.className = 'empty';
}

function getBar() {
	var bar = document.getElementById("info_bar");
	return bar;
}

function writeInfoBar(msg, type) {

	var bar = getBar();
	bar.innerHTML = '';
	bar.className = '';

	var barString = '<div id="info_image_wrap"><img src="images/icons/stylistica/48x48/info.png" class="icon-medium" />';

	if(type == 'positive'){
		barString += '<img src="images/icons/stylistica/48x48/like.png" class="icon-medium" />';
		bar.className += 'positive';
	}
	else if(type == 'negative'){
		barString += '<img src="images/icons/stylistica/48x48/warning.png" class="icon-medium" />';
		bar.className += 'negative';
	}
	else if(type == 'neutral'){
		barString += '<img src="images/icons/stylistica/48x48/comment.png" class="icon-medium" />';
		bar.className += 'neutral';
	}

	barString += '</div><p class="info">' + msg + '</p><p id="closer">close</p>';
	bar.innerHTML = barString;

	var closer = document.getElementById("closer");
	closer.addEventListener("click", barDestroyer, false);
}

var theseButtons =  document.getElementsByTagName('button');

for(var i = 0; i < theseButtons.length; i++){
	var thisButton = theseButtons[i];
	thisButton.addEventListener("click", barMaker, false);
}