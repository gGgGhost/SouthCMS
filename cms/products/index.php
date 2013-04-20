<?php
// Link into the api
$directory = __DIR__;
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";

// Hook up to database
$db_link = getConnected();

// Get the desired product, based on GET parameter
$id = $_GET['id'];
$product = getProduct($id, $db_link);

// Get page string for this product, formatted for the cms
$page = prepareProductPage('cms', $product);

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link);

?>