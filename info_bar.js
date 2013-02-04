// Info bar
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

	var barString = '<div id="info_image_wrap"><img src="images/icons/48x48/info.png" class="icon-medium" />';

	if(type === 'positive'){
		barString += '<img src="images/icons/48x48/like.png" class="icon-medium" />';
		bar.className += 'positive';
	}
	else if(type === 'negative'){
		barString += '<img src="images/icons/48x48/warning.png" class="icon-medium" />';
		bar.className += 'negative';
	}
	else if(type === 'neutral'){
		barString += '<img src="images/icons/48x48/comment.png" class="icon-medium" />';
		bar.className += 'neutral';
	}

	barString += '</div><p class="info">' + msg + '</p><p id="closer">close</p>';
	bar.innerHTML = barString;

	var closer = document.getElementById("closer");
	closer.addEventListener("click", barDestroyer, false);
}