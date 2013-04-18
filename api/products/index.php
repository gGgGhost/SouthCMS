<?php
$directory = __DIR__;

require_once "$directory/../config.php";
require_once "$directory/../database.php";


$db_link 	= 	getConnected($db_login);
$method  	= 	$_SERVER['REQUEST_METHOD'];

switch ($method) {
	case 'GET': 
		if (isset($_GET['id'])) {
			$id = retrieveFromGET('id', $db_link);
			getProduct($db_link, $id);
		}
		break;
	case 'POST': 
		addProduct($db_link);
		break;
}

mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');

function getProduct($link, $id, $format = 'array') {

	$query = "SELECT * FROM products WHERE id = '$id'";

	try  {
		if (!$result = mysqli_query($link, $query)) {	
			$error = mysqli_error($link);
			throw new Exception("<p>Could not submit query.</p>" .
				"<p>\"$error\"</p>");
		} else {
			
			try {
				if (!$row = mysqli_fetch_assoc($result)) {
					$error = mysqli_error($link);
					throw new Exception("<p>Could not retrieve row.</p>" .
					"<p>\"$error\"</p>");
				} else {
					return $row;
				}			
			} catch (Exception $e) {
				bugger($e->getMessage());
			}
		}

	} catch (Exception $e) {
			bugger($e->getMessage());
	}


}


function addProduct($link){

	if (isset($_POST['name']) &&
		isset($_POST['description']) &&
		isset($_POST['cost']) &&
		isset($_POST['price']) &&
		isset($_POST['quantity']) &&
		isset($_POST['code'])) {

		$name = retrieveFromPOST('name', $link);
		$description = retrieveFromPOST('description', $link);
		$cost = retrieveFromPOST('cost', $link);
		$price = retrieveFromPOST('price', $link);
		$quantity = retrieveFromPOST('quantity', $link);
		$code = retrieveFromPOST('code', $link);

		$query = "INSERT INTO products (name, description, cost, " .
				"price, stock, code) VALUES " .
				"('$name', '$description', '$cost', " .
				"'$price', '$quantity', '$code')";

		if ($results[] = queryDatabase($link, $query)) {
				echo("<p>Product Added: $name</p>");
		}
	} else {
		echo("Variables not set correctly");
	}

}

function retrieveFromPOST($var, $link) {
	return mysqli_escape_string($link, $_POST[$var]);
}

function retrieveFromGET($var, $link) {
	return mysqli_escape_string($link, $_GET[$var]);
}

function countProducts($login) {
	$db_link = getConnected($login);
	$query = "SELECT COUNT(*) FROM products";
	$result = queryDatabase($db_link, $query);
	$row = mysqli_fetch_row($result);
	return $row[0];
}

?>