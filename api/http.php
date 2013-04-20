<?php
function retrieveVarFromPOST($var, $db_link) {
	return mysqli_escape_string($db_link, $_POST[$var]);
}

function retrieveVarFromGET($var, $db_link) {
	return mysqli_escape_string($db_link, $_GET[$var]);
}
?>