<?php

echo <<<END
	<!DOCTYPE html>
	<html>
	<body>
	<div id="phpstuff">
END;

require_once '../login.php';
require_once '../vars.php';
require_once '../util.php';

$db_link = getConnected($db_login, $db_vars);
$table_name = $db_vars['table'];

$method = $_SERVER['REQUEST_METHOD'];

switch ($method)
{
	case 'GET': 
		if(isset($_GET['id'])){
			$id = retrieveFromGET('id', $db_link);
			getProduct($db_link, $table_name, $id);
		}
		break;
	case 'POST': 
		addProduct($db_link, $table_name);
		break;
}

if ($result = mysqli_close($db_link)){
	bugger("<p class='bugger'>Database connection severed!</p>");
}

function getProduct($link, $table, $id){

	$query = "SELECT * FROM $table WHERE id = '$id'";

	try 
	{
		if (!$result = mysqli_query($link, $query))
		{
			throw new Exception("<p class='bugger'>Could not perform query.</p>" .
				"<p class='bugger'>" . mysqli_error($link) . "</p>");
		}
		else
		{
			bugger("<p class='bugger'>Query successful!</p>");
			
			try
			{
				if (!$row = mysqli_fetch_assoc($result))
				{
					throw new Exception("<p class='bugger'>Could not retrieve row.</p>" .
					"<p class='bugger'>" . mysqli_error($link) . "</p>");
				}
				else
				{
					bugger("<p class='bugger'>Row retrieved!</p>");
					$name = $row['name'];
					$cost = $row['cost'];
					bugger("<p>Name: $name.</p><p>Cost per unit: &pound;$cost.</p>");
					// Send $row to page
				}				
			} catch (Exception $e){
				bugger($e->getMessage());
			}
		}

	} catch (Exception $e){
			bugger($e->getMessage());
	}


}


function addProduct($link, $table){


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

		$query = "INSERT INTO $table (name, description, cost, " .
				"price, stock, code) VALUES " .
				"('$name', '$description', '$cost', " .
				"'$price', '$quantity', '$code')";

		try 
		{
			if(!$results[] = mysqli_query($link, $query)){
				throw new Exception("<p class='bugger'>Query failed: $query</p>" . 
					"<p class='bugger'>" . mysqli_error($link) . "</p>");
			}
			else{
				bugger("New Product Added!");
			}
		} catch (Exception $e)
		{
			bugger($e->getMessage());
		}
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

function printThisProduct(){


}

echo <<<END
	</div>
	</body>
	</html>
END;
?>