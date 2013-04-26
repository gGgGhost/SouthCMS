<?php
$directory = __DIR__;
require_once "$directory/../config.php";
require_once "$directory/../database.php";
require_once "$directory/../shared.php";

$method  	= 	$_SERVER['REQUEST_METHOD'];

$db_link = getConnected();

switch ($method) {
	case 'GET': 
		break;
	case 'POST': 
		submitOrder($db_link);
		break;
}

mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');

function submitOrder($db_link){

if (isset($_POST['name']) &&
	isset($_POST['address']) &&
	isset($_POST['postcode']) &&
	isset($_POST['email']) &&
	isset($_POST['basket'])) {

	$name = retrieveVarFromPOST('name', $db_link);
	$address = retrieveVarFromPOST('address', $db_link);
	$postcode = retrieveVarFromPOST('postcode', $db_link);
	$email = retrieveVarFromPOST('email', $db_link);
	$basketString = stripslashes(retrieveVarFromPOST('basket', $db_link));
	$basket = json_decode($basketString, true);

	$query = "INSERT INTO customers (fullname, address, postcode, " .
			"email) VALUES ('$name', '$address', '$postcode', " .
			"'$email')";
	$result = queryDatabase($query, $db_link);

	$query = "SELECT cust_id FROM customers WHERE email='$email'";
	$result = queryDatabase($query, $db_link);

	$cust_id = retrieveUsingResult($result, $db_link)['cust_id'];


	$query = "INSERT INTO orders (cust_id) VALUES ('$cust_id')";
	$result = queryDatabase($query, $db_link);

	$query = "SELECT order_id FROM orders WHERE cust_id='$cust_id'" .
	 		 " ORDER BY order_id DESC";
	$result = queryDatabase($query, $db_link);

	$order_id = retrieveUsingResult($result, $db_link)['order_id'];

	for ($i = 0; $i < count($basket); $i++) {
		$productId = $basket[$i]['productId'];
		$quantity = $basket[$i]['quantity'];
		$query = "INSERT INTO in_order (prodnum, order_id, quantity)" .
				" VALUES ('$productId', '$order_id', '$quantity')";
		$result = queryDatabase($query, $db_link);
	}

} else {
	echo("Variables not set correctly");
}
}
function howManyOrders($completed, $db_link) {
	$query = "SELECT COUNT(*) FROM orders WHERE completed=$completed";
	$result = queryDatabase($query, $db_link);
	$amount = retrieveUsingResult($result, $db_link);
	return $amount;
}

?>