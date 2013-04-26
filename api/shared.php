<?php
/*
Functions needing to be shared across multiple files
*/
function retrieveVarFromPOST($var, $db_link) {
	return mysqli_escape_string($db_link, $_POST[$var]);
}
function retrieveVarFromGET($var, $db_link) {
	return mysqli_escape_string($db_link, $_GET[$var]);
}

function wrapWithLink ($href, $string) {
	$wrappedString = "<a href='$href'>"
					. $string
					. "</a>";
	return $wrappedString;
}
function isThereEnoughStock ($stock) {

	switch ($stock) {
			case 0:
				$stockMessage = "Out of Stock";
				break;
			default:
				$stockMessage = "$stock in stock";
	}

	return $stockMessage;
}
function addCategory ($catname, $db_link) {

	$query = "INSERT INTO categories (catname) VALUES " .
			"('$catname')";
			
	if ($results[] = queryDatabase($query, $db_link)) {
		
echo <<<END
<div class='addition'>
<p class="top">Category Added: "<a href="categories/?name=$catname">$catname</a>"</p>
</div>
END;
	}
}
function getCategoryNumber($name, $db_link) {
	$query = "SELECT catnum FROM categories WHERE catname = '$name'";
	$result = queryDatabase($query, $db_link);
	$row = retrieveUsingResult($result, $db_link);
	$catnum = $row['catnum'];
	return $catnum;
}
?>