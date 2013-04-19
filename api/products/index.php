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

	try  {
		if (!$result = mysqli_query($db_link, $query)) {	
			$error = mysqli_error($db_link);
			throw new Exception("<p>Could not submit query.</p>" .
				"<p>\"$error\"</p>");
		} else {
			
			try {
				if (!$row = mysqli_fetch_assoc($result)) {
					$error = mysqli_error($db_link);
					throw new Exception("<p>Could not retrieve row.</p>" .
					"<p>$error</p>");
				} else {
					return $row;
				}			
			} catch (Exception $e) {
				bugger($e->getMessage());
			}
		}

	} catch (Exception $e) {
			bugger($e->getMessage());
	}


}


function addProduct($link){


	if (isset($_POST['name']) &&
		isset($_POST['description']) &&
		isset($_POST['cost']) &&
		isset($_POST['price']) &&
		isset($_POST['quantity']) &&
		isset($_POST['code'])) {

		$name = retrieveFromPOST('name', $link);
		$description = retrieveFromPOST('description', $link);
		$cost = retrieveFromPOST('cost', $link);
		$price = retrieveFromPOST('price', $link);
		$quantity = retrieveFromPOST('quantity', $link);
		$code = retrieveFromPOST('code', $link);

		$query = "INSERT INTO products (name, description, cost, " .
				"price, stock, code) VALUES " .
				"('$name', '$description', '$cost', " .
				"'$price', '$quantity', '$code')";

		if ($results[] = queryDatabase($link, $query)) {
			$initialValue = $cost * $quantity;
			$salesValue = $price * $quantity;
			$difference = $salesValue - $initialValue;
			$dpi = $price - $cost; // Difference Per Item
			$id = countProducts($link);
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

function retrieveFromPOST($var, $link) {
	return mysqli_escape_string($link, $_POST[$var]);
}

function retrieveFromGET($var, $link) {
	return mysqli_escape_string($link, $_GET[$var]);
}

function countProducts($db_link) {
	$query = "SELECT COUNT(*) FROM products";
	$result = queryDatabase($db_link, $query);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

function displayProductList($limit, $totalProducts, $db_link) {
	$thisProduct;
	$productId = $totalProducts;

	for ($i = 0; $i < $limit; $i++) {
		$thisProduct = getProduct($productId, $db_link);
		$name = $thisProduct['name'];
		$price = $thisProduct['price'];
		$stock = $thisProduct['stock'];
		switch ($stock) {
			case 0:
				$stockMessage = "Out of Stock";
				break;
			default:
				$stockMessage = "$stock in Stock";
		}

echo <<<END
<div class="product">
<h2><a href="products/?id=$productId">$name</a></h2>
<p>$stockMessage</p>
<p>&pound;$price</p>
</div>

END;

		$productId--;
		if ($productId == 0) { break; }
	}
}

?>