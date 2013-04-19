<?php

$directory = __DIR__;
require_once "$directory/../api/products/index.php";

$db_link = getConnected();
$id = $_GET['id'];
$product = getProduct($id, $db_link);

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



echo <<<END
</div>
</body>
</html>
END;

mysqli_close($db_link);

?>