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

		function getProduct($link){
			if (isset($_GET['id']))
			{
				$id = retrieveFromGET('id', $db_link)

			}

			function retrieveFromGET($var, $link) 
			{
				return mysqli_escape_string($link, $_GET[$var]);
			}

		}


		function addProduct($link){

			if (isset($_POST['name']) &&
				isset($_POST['description']) &&
				isset($_POST['cost']) &&
				isset($_POST['price']) &&
				isset($_POST['quantity']) &&
				isset($_POST['code']))
			{
				$name = retrieveFromPOST('name', $db_link);
				$description = retrieveFromPOST('description', $db_link);
				$cost = retrieveFromPOST('cost', $db_link);
				$price = retrieveFromPOST('price', $db_link);
				$quantity = retrieveFromPOST('quantity', $db_link);
				$code = retrieveFromPOST('code', $db_link);

				$query = "INSERT INTO $table_name VALUES " .
						"('$name', '$description', '$cost', " .
							"'$price', '$quantity', '$code')";

				$results[] = mysqli_query($db_link, $query);

			}

			function retrieveFromPOST($var, $link) 
			{
				return mysqli_escape_string($link, $_POST[$var]);
			}

		}
?>