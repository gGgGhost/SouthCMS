<?php
require_once '../config.php';
require_once '../database.php';

$db_link 	= 	getConnected($db_login);
$method  	= 	$_SERVER['REQUEST_METHOD'];

switch ($method)
{
	case 'GET': 
		if (isset($_GET['id']))
		{
			$id = retrieveFromGET('id', $db_link);
			getProduct($db_link, $id);
		}
		break;
	case 'POST': 
		addProduct($db_link);
		break;
}

mysqli_close($db_link);

function getProduct($link, $id, $format = 'array'){

	$query = "SELECT * FROM products WHERE id = '$id'";

	try 
	{
		if (!$result = mysqli_query($link, $query))
		{	
			$error = mysqli_error($link);
			throw new Exception("<p>Could not submit query.</p>" .
				"<p>\"$error\"</p>");
		}
		else
		{
			
			try
			{
				if (!$row = mysqli_fetch_assoc($result))
				{
					$error = mysqli_error($link);
					throw new Exception("<p>Could not retrieve row.</p>" .
					"<p>\"$error\"</p>");
				}			
			} catch (Exception $e){
				bugger($e->getMessage());
			}
		}

	} catch (Exception $e){
			bugger($e->getMessage());
	}


}


function addProduct($link){
	echo(var_dump($_POST));

	if (isset($_POST['name']) &&
		isset($_POST['description']) &&
		isset($_POST['cost']) &&
		isset($_POST['price']) &&
		isset($_POST['quantity']) &&
		isset($_POST['code']))
	{
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

		try 
		{
			if (!$results[] = mysqli_query($link, $query))
			{
				$error = mysqli_error($link);
				throw new Exception("<p>Query failed: $query</p>" . 
					"<p>\"$error\"</p>");
			}
			else
			{
				echo("<p>Product Added: $name</p>");
			}
		} catch (Exception $e)
		{
			bugger($e->getMessage());
		}
	}
	else{
		echo("Variables not set correctly");
	}

}

function retrieveFromPOST($var, $link) 
{
	return mysqli_escape_string($link, $_POST[$var]);
}

function retrieveFromGET($var, $link) 
{
	return mysqli_escape_string($link, $_GET[$var]);
}

?>