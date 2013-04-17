<?php
		require_once '../login.php';
		require_once '../vars.php';
		require_once '../util.php';

		$db_link = getConnected($db_login, $db_vars);
		$table_name = $db_vars['table'];

		$method = $_SERVER['REQUEST_METHOD'];

		switch($method){
			case 'GET': getProduct();
			case 'POST': addProduct();
		}

		function getProduct(){

		}


		function addProduct(){

			if (isset($_POST['name']) &&
				isset($_POST['description']) &&
				isset($_POST['cost']) &&
				isset($_POST['price']) &&
				isset($_POST['quantity']) &&
				isset($_POST['code']))
			{
				$name = getFromPOST('name', $db_link);
				$description = getFromPOST('description', $db_link);
				$cost = getFromPOST('cost', $db_link);
				$price = getFromPOST('price', $db_link);
				$quantity = getFromPOST('quantity', $db_link);
				$code = getFromPOST('code', $db_link);

				$query = "INSERT INTO $table_name VALUES " .
						"('$name', '$description', '$cost', " .
							"'$price', '$quantity', '$code')";

				$results[] = mysqli_query($db_link, $query);

			}

			function getFromPOST($var, $link) 
			{
				return mysqli_escape_string($link, $_POST[$var]);
			}

		}
?>