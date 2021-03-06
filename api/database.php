<?php
/*
The daddy functions
Used by everything to connect to the database
*/
function getConnected() {
	global $DB_LOGIN;
	$db_host = $DB_LOGIN['host'];
	$db_user = $DB_LOGIN['user'];
	$db_pass = $DB_LOGIN['pass'];
	$db_name = $DB_LOGIN['DB'];

	try {
		if (!$db_link = mysqli_connect($db_host, $db_user, $db_pass)) {
			throw new Exception("Could not connect to MySQL because: " . mysqli_error($db_link));
		}
	} catch (Exception $e) {
		bugger($e->getMessage());
		// Do something about the failed connection
	}
	try {
		if (!$result = mysqli_select_db($db_link, $db_name)) {
			throw new Exception("<p>Could not select DB because it does not exist.</p>");
		}
	} catch (Exception $e) {
			createDatabase($db_link, $db_name, $db_user, $db_host, $db_pass);
	} 
		
	return $db_link;
}

function createDatabase($db_link, $name, $user, $host, $pass) {

		// Feed queries for creation of database and tables into array query[]
		$query[] = "CREATE DATABASE $name";
		$query[] = "GRANT SELECT, INSERT, UPDATE ON $name.* " .
					"TO '$user'@'$host' IDENTIFIED BY '$pass'";
		$query[] = "USE $name";
		$query[] = "CREATE TABLE products (name VARCHAR(40) NOT NULL, description VARCHAR(140), " . 
					"cost DEC(6,2), price DEC(6,2), stock MEDIUMINT DEFAULT 0, code VARCHAR(20)," .
					"prodnum INT UNSIGNED NOT NULL AUTO_INCREMENT, FULLTEXT(name), " .
					"catnum INT UNSIGNED NOT NULL, INDEX(code), PRIMARY KEY(prodnum)) " .
					"ENGINE MyISAM";
		$query[] = "CREATE TABLE categories (catname VARCHAR(15) NOT NULL, catnum INT UNSIGNED" .
					" NOT NULL AUTO_INCREMENT, PRIMARY KEY(catnum))";
		$query[] = "ALTER TABLE products ADD FOREIGN KEY(catnum) REFERENCES categories";
		$query[] = "CREATE TABLE customers (fullname VARCHAR(25) NOT NULL, address VARCHAR(40) NOT NULL," .
					" postcode CHAR(7) NOT NULL, email VARCHAR(40) NOT NULL, cust_id INT UNSIGNED" .
					" NOT NULL AUTO_INCREMENT, PRIMARY KEY(cust_id))";
		$query[] = "CREATE TABLE orders (order_id INT UNSIGNED NOT NULL AUTO_INCREMENT," .
				   " cust_id INT UNSIGNED NOT NULL, price DEC(6,2) NOT NULL, order_date TIMESTAMP NOT NULL " .
				   "DEFAULT CURRENT_TIMESTAMP, completed BOOLEAN NOT NULL DEFAULT FALSE,".
				   " PRIMARY KEY(order_id)) ENGINE MyISAM";
		$query[] = "ALTER TABLE orders ADD FOREIGN KEY(cust_id) REFERENCES customers";
		$query[] = "CREATE TABLE in_order (record INT UNSIGNED NOT NULL AUTO_INCREMENT, " .
				   "order_id INT UNSIGNED NOT NULL, prodnum INT UNSIGNED NOT NULL, " .
				   "quantity MEDIUMINT NOT NULL DEFAULT 1, PRIMARY KEY(record)) ENGINE MyISAM";
		$query[] = "ALTER TABLE in_order ADD FOREIGN KEY(prodnum) REFERENCES products, " .
				   "ADD FOREIGN KEY(order_id) REFERENCES orders";

		// Cycle through queries and execute each one in turn
		foreach ($query as $q) {

			// If query is USE, select db instead of querying db with string
			if($q == "USE $name;") { 
				mysqli_select_db($db_link, $name) 
					or die("Unable to select database: " . mysqli_error($db_link));
			} else {
				$results[] = queryDatabase($q, $db_link);
			}
			
		}
}
/*
Submit the passed query string to MySQL
Return the result or echo error
*/
function queryDatabase($query, $db_link) {


	try {
		if(!$result = mysqli_query($db_link, $query)) {
			$error = mysqli_error($db_link);
			throw new Exception("<p>Failed on query: $query</p>" .
				"<p>Because: $error</p>");
		} 
	} catch (Exception $e) {
			echo ($e->getMessage());
	}
	return $result;
}
/*
Use the passed result of a MySQL query to retrieve the row from the DB
In the form of an associative array
Return the result or echo error
*/
function retrieveUsingResult ($result, $db_link, $msg = "", $format = "array") {
	try {
			if (!$row = mysqli_fetch_assoc($result)) {
				$error = mysqli_error($db_link);
				throw new Exception("<p>Could not retrieve row.</p>" .
				"<p>$error</p><p>$msg</p>");
			} else {
				return $row;
			}			
		} catch (Exception $e) {
			bugger($e->getMessage());
		}
}
/*
Use the passed result of a MySQL query to retrieve the row from the DB
In the form of an associative array
Return the result or echo error
*/
function retrieveProductIds ($limit, $db_link, $category = 'all', $offset = 0) {
	$query = "SELECT prodnum FROM products";

	switch ($category) {
		case 'all':
			break;
		default:
			$query = $query . ", categories WHERE products.catnum = categories.catnum
			  AND catname='$category'";
	}

	$query = $query . " ORDER BY prodnum DESC LIMIT $limit";
	$result = queryDatabase($query, $db_link);
	$stopHere = mysqli_num_rows($result);

	for ($i = 0; $i < $stopHere; $i++) {
		$row = retrieveUsingResult($result, $db_link);
		$prodnums[] = $row['prodnum'];
	}
	return $prodnums;
}

function bugger($msg) {
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>