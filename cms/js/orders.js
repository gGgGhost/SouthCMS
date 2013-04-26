
window.onload = function (e) {
	fixTheButtons();
}

function orderCompletion (e) {
	var responseArea = document.getElementById('order_area');
	var button = e.target;
	var id = button.id.slice(3);
	var request = 'id=' + id;
	var result = ajaxRequest('POST', '', request, responseArea, true);
	fixTheButtons();
}

function fixTheButtons () {
	var buttons = document.getElementsByClassName('complete');

	for (var i = 0; i < buttons.length; i++) {
		var button = buttons[i];
		button.addEventListener('click', orderCompletion, false);
	}
	return buttons;
}