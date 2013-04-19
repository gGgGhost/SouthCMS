<?php

$directory = __DIR__;
require_once "$directory/../api/products/index.php";

$db_link = getConnected();

$totalProducts = countProducts($db_link);
$numberToDisplay = 5;

echo <<<END
<!DOCTYPE html>
<html>
<head>
<title>shop</title>
<link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<header>
<h1>very basic shop</h1>
</header>
<div class="main_section">
<form id="search_bar">
<label for="search_box">Search:</label>
<input type="search" name="search_box" /><input type="submit" value="Go" />
</form>
<h1>Latest Products</h1>
<div id="product_list">
END;

switch ($totalProducts) {
	case 0:
		echo ("<h2>There are no products in the store yet to display.</h2>");
		break;
	default: 
		displayProductList($numberToDisplay, $totalProducts, $db_link);
		break;
}

echo <<<END
</div>
</div>
</body>
</html>
END;

mysqli_close($db_link);

?>