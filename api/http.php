<?php
function retrieveVarFromPOST($var, $db_link) {
	return mysqli_escape_string($db_link, $_POST[$var]);
}

function retrieveVarFromGET($var, $db_link) {
	return mysqli_escape_string($db_link, $_GET[$var]);
}

function getCategoryNumber($name, $db_link) {
	$query = "SELECT catnum FROM categories WHERE catname = '$name'";
	$result = queryDatabase($query, $db_link);
	$row = retrieveUsingResult($result, $db_link);
	$catnum = $row['catnum'];
	return $catnum;
}
?>