<?php
// Link into the api
$directory = __DIR__;
require_once "$directory/../../api/products/index.php";

// Hook up to database
$db_link = getConnected();

// Get the desired product, based on GET parameter
$id = $_GET['id'];
$product = getProduct($id, $db_link);

// Display product details
printProductPagePurchasable($product);

// End connection
mysqli_close($db_link);

?>