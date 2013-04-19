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

	if ($results[] = queryDatabase($query, $link)) {
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
$result = queryDatabase($query, $db_link);
$row = mysqli_fetch_row($result);
return $row[0];
}

function printProductList($limit, $db_link) {

	$ids = retrieveLatestIds($limit, $db_link);
	$lastProduct = $ids[$limit -1];


for ($i = 0; $i < $limit; $i++) {
	$productId = $ids[$i];
	$thisProduct = getProduct($productId, $db_link);
	$name = $thisProduct['name'];
	$price = $thisProduct['price'];
	$stock = $thisProduct['stock'];
	
	$stockMessage = isThereEnoughStock($stock);

echo <<<END
<div class="product">
<h2><a href="products/?id=$productId">$name</a></h2>
<p class="stock_level">$stockMessage</p>
<p class="price">&pound;$price</p>
</div>

END;
		if ($productId == $lastProduct) { break; }
	}
}

function retrieveLatestIds ($limit, $db_link) {
$query = "SELECT id FROM products ORDER BY id DESC LIMIT $limit";
$result = queryDatabase($query, $db_link);
$stopHere = mysqli_num_rows($result);

for ($i = 0; $i < $stopHere; $i++) {
	$row = retrieveUsingResult($result, $db_link);
	$ids[] = $row['id'];
}
return $ids;
}

function printProductPageEditable ($product) {
$name = $product['name'];
$cost = $product['cost'];
$price = $product['price'];
$stock = $product['stock'];
$code = $product['code'];
$id = $product['id'];
$description = $product['description'];

// Top of page
echo <<<END
<!DOCTYPE html>
<html>
<head>
	<title>SouthCMS E-commerce</title>
	<link href="../style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<header>
	<h1>SouthCMS</h1>
</header>
<div id="main_section">
END;
// Print product
echo <<<END
<div id="product_area">
<h2 id="product_name">$name</h2>
<p id="product_description"><h3>description</h3>$description</p>
<p id="product_cost"><h3>cost (per unit)</h3>&pound;$cost</p>
<p id="product_price"><h3>price (per unit)</h3>&pound;$price</p>
<p id="product_id"><h3>id</h3>$id</p>
<p id="product_code"><h3>code</h3>$code</p>
<p id="product_stock"><h3>current stock</h3>$stock</p>
<button id="edit">Edit product</button>
</div>
END;
// Close tags
echo <<<END
</div>
</body>
</html>
END;
}

function printProductPagePurchasable ($product) {

$name = $product['name'];
$price = $product['price'];
$stock = $product['stock'];
$description = $product['description'];

$stockMessage = isThereEnoughStock($stock);

// Top of page
echo <<<END
<!DOCTYPE html>
<html>
<head>
	<title>shop</title>
	<link href="../style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<header>
	<h1>very basic shop</h1>
</header>
<div id="main_section">
<form id="search_bar">
	<label for="search_box">Search:</label>
	<input type="search" name="search_box" /><input type="submit" value="Go" />
</form>
END;
// Print product
echo <<<END
<div id="product_area">
<h2 id="product_name">$name</h2>
<p id="product_description"><h3>description</h3>$description</p>
<p id="product_price">&pound;$price</p>
<p id="product_stock">$stockMessage</p>
<button id="buy">Add to Basket</button>
</div>
END;
// Close tags
echo <<<END
</div>
</body>
</html>
END;

}

function isThereEnoughStock($stock) {
switch ($stock) {
		case 0:
			$stockMessage = "Out of Stock";
			break;
		default:
			$stockMessage = "$stock in stock";
}
return $stockMessage;
}

?>