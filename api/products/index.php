<?php
$directory = __DIR__;
require_once "$directory/../config.php";
require_once "$directory/../database.php";

$method  	= 	$_SERVER['REQUEST_METHOD'];

$db_link = getConnected();

switch ($method) {
	case 'GET': 
		if (isset($_GET['id'])) {
			$id = retrieveFromGET('id', $db_link);
			getProduct($id, $db_link);
		}
		break;
	case 'POST': 
		addProduct($db_link);
		break;
}

mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');


function getProduct($id, $db_link, $format = 'array') {

	$query = "SELECT * FROM products WHERE id = '$id'";

	$result = queryDatabase($query, $db_link);	

	$product = retrieveUsingResult($result, $db_link);

	return $product;

}


function addProduct($db_link){


if (isset($_POST['name']) &&
	isset($_POST['description']) &&
	isset($_POST['cost']) &&
	isset($_POST['price']) &&
	isset($_POST['quantity']) &&
	isset($_POST['code'])) {

	$name = retrieveFromPOST('name', $db_link);
	$description = retrieveFromPOST('description', $db_link);
	$cost = retrieveFromPOST('cost', $db_link);
	$price = retrieveFromPOST('price', $db_link);
	$quantity = retrieveFromPOST('quantity', $db_link);
	$code = retrieveFromPOST('code', $db_link);

	$query = "INSERT INTO products (name, description, cost, " .
			"price, stock, code) VALUES " .
			"('$name', '$description', '$cost', " .
			"'$price', '$quantity', '$code')";

	if ($results[] = queryDatabase($query, $db_link)) {
		$initialValue = $cost * $quantity;
		$salesValue = $price * $quantity;
		$difference = $salesValue - $initialValue;
		$dpi = $price - $cost; // Difference Per Item
		$id = retrieveLatestIds(1, $db_link)[0];


echo <<<END
<div class='addition'>
<a href="products/?id=$id"><p class="top">Product Added: "$name" x$quantity</p></a>
<div>
<p>Initial cost: £$initialValue (£$cost per item)</p>
<p>Sales value: £$salesValue (£$price per item)</p>
<p>Difference: £$difference (£$dpi per item)</p>
</div>
</div>
END;

	}
} else {
	echo("Variables not set correctly");
}

}

function retrieveFromPOST($var, $db_link) {
	return mysqli_escape_string($db_link, $_POST[$var]);
}

function retrieveFromGET($var, $db_link) {
	return mysqli_escape_string($db_link, $_GET[$var]);
}

function countProducts($db_link) {
	$query = "SELECT COUNT(*) FROM products";
	$result = queryDatabase($query, $db_link);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

?>