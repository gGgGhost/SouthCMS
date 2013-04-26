<?php
/*
This page provides functions for manipulating orders.

GET: no function
POST: submitOrder

*/

/*
>>>>>>Script body
*/
$directory = __DIR__; // Where the file is
// Include the necessary files
require_once "$directory/../config.php";
require_once "$directory/../database.php";
require_once "$directory/../shared.php";

// Find out how page was accessed
$method  	= 	$_SERVER['REQUEST_METHOD'];

// Establish a link to the database for the duration of the script
$db_link = getConnected();

// Take appropriate action
switch ($method) {
	case 'GET': 
		break;
	case 'POST':
		if(!isset($_POST['id'])){
			submitOrder($db_link);
		}
		
		break;
}
// Close the connection when finished
mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');
/*
>>>>>>Functions
*/
function submitOrder($db_link){

// Make sure the right variables are there
if (isset($_POST['name']) &&
	isset($_POST['address']) &&
	isset($_POST['postcode']) &&
	isset($_POST['email']) &&
	isset($_POST['basket'])) {

	// Retrieve them all
	$name = retrieveVarFromPOST('name', $db_link);
	$address = retrieveVarFromPOST('address', $db_link);
	$postcode = retrieveVarFromPOST('postcode', $db_link);
	$email = retrieveVarFromPOST('email', $db_link);
	$basketString = stripslashes(retrieveVarFromPOST('basket', $db_link));
	$basket = json_decode($basketString, true);
	$price = 0;

	for ($i = 0; $i < count($basket); $i++) {
		$price += $basket[$i]['price'];
	}

	$query = "INSERT INTO customers (fullname, address, postcode, " .
			"email) VALUES ('$name', '$address', '$postcode', " .
			"'$email')";
	$result = queryDatabase($query, $db_link);

	$query = "SELECT cust_id FROM customers WHERE email='$email'";
	$result = queryDatabase($query, $db_link);

	$cust_id = retrieveUsingResult($result, $db_link)['cust_id'];


	$query = "INSERT INTO orders (cust_id, price) VALUES ('$cust_id', '$price')";
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


?>