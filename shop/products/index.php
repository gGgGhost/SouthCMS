<?php

$directory = __DIR__;
require_once "$directory/../../api/products/index.php";

$db_link = getConnected();
$id = $_GET['id'];

$product = getProduct($id, $db_link);

printProductPagePurchasable($product);

// End connection
mysqli_close($db_link);

?>