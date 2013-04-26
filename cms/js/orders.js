
window.onload = function (e) {
	var buttons = document.getElementsByClassName('complete');

	for (var i = 0; i < buttons.length; i++) {
		var button = buttons[i];
		button.addEventListener('click', orderCompletion, false);
	}
}

function orderCompletion (e) {
	var responseArea = document.getElementById('order_area');
	var button = e.target;
	var id = button.id.slice(3);
	var request = 'id=' + id;
	ajaxRequest('POST', '', request, responseArea, true);
}