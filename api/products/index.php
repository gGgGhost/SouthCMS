<?php
$directory = __DIR__;
require_once "$directory/../config.php";
require_once "$directory/../database.php";
require_once "$directory/../shared.php";

$method  	= 	$_SERVER['REQUEST_METHOD'];

$db_link = getConnected();

switch ($method) {
	case 'GET': 
		if (isset($_GET['id'])) {
			$id = retrieveVarFromGET('id', $db_link);
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

	$query = "SELECT name, description, catname, cost, price,".  
			 "stock, code FROM products, categories ".
			 "WHERE prodnum = '$id' AND products.catnum = categories.catnum";

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
	isset($_POST['code']) &&
	isset($_POST['catname'])) {
	

	$name = retrieveVarFromPOST('name', $db_link);
	$description = retrieveVarFromPOST('description', $db_link);
	$cost = retrieveVarFromPOST('cost', $db_link);
	$price = retrieveVarFromPOST('price', $db_link);
	$quantity = retrieveVarFromPOST('quantity', $db_link);
	$code = retrieveVarFromPOST('code', $db_link);
	$category = retrieveVarFromPOST('catname', $db_link);

	if (isset($_POST['newcat'])) {
		addCategory($category, $db_link);
	}

	$catnum = getCategoryNumber($category, $db_link);

	$query = "INSERT INTO products (name, description, cost, " .
			"price, stock, code, catnum) VALUES " .
			"('$name', '$description', '$cost', " .
			"'$price', '$quantity', '$code', '$catnum')";

	if ($results[] = queryDatabase($query, $db_link)) {
		$initialValue = $cost * $quantity;
		$salesValue = $price * $quantity;
		$difference = $salesValue - $initialValue;
		$dpi = $price - $cost; // Difference Per Item
		$id = retrieveProductIdFromName($name, $db_link);


echo <<<END
<div class='addition'>
<p class="top">Product Added: "<a href="products/?id=$id">$name</a>" x$quantity</p>
<div class="extra_details empty">
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

function countProducts($db_link) {
	$query = "SELECT COUNT(*) FROM products";
	$result = queryDatabase($query, $db_link);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

function retrieveProductIdFromName($name, $db_link) {
	$query = "SELECT prodnum FROM products WHERE name='$name'";
	$result = queryDatabase($query, $db_link);
	$row = retrieveUsingResult($result, $db_link);
	$prodnum = $row['prodnum'];
	return $prodnum;
}

?>