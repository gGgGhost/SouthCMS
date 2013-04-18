<?php
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
END;
$directory = __DIR__;
require_once "$directory/../api/products/index.php";

countProducts($db_login);
function displayProductList($limit, $offset, $totalProducts) {
	
}


echo <<<END
</div>
</body>
</html>
END;

?>