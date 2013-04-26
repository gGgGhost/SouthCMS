<?php
/*

*/
$directory = __DIR__;
require_once "$directory/../config.php";
require_once "$directory/../database.php";
require_once "$directory/../shared.php";

$method  	= 	$_SERVER['REQUEST_METHOD'];

$db_link = getConnected();


mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');


function getSales($db_link) {
	$totalPrice = 0;
	$query = "SELECT price FROM orders WHERE completed=true";

	$result = queryDatabase($query, $db_link);	
	$num = mysqli_num_rows($result);
	if ($num > 0) {
		for ($i = 0; $i < $num; $i++) {
		$row = retrieveUsingResult($result, $db_link);
		$totalPrice += $row['price'];
		}
		$sales = "<p>Sold $num products for a total of &pound;$totalPrice</p>";
	}
	else {
		$sales = "<h3>No sales</h3>";
	}
	
	return $sales;

}


?>