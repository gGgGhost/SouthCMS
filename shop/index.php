<?php
// Link into the api
require_once "../api/products/index.php";
require_once "../api/page.php";

// Hook up to database
$db_link = getConnected();

// Get the numbers
$totalProducts = countProducts($db_link);
$numberToDisplay = 6;

// Reduce number if not enough products in DB to meet the desired amount
if ($numberToDisplay > $totalProducts) { $numberToDisplay = $totalProducts; }

// Get page string for this product, formatted for the shop
$page = prepareShopFront($numberToDisplay, $db_link);

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link);

?>