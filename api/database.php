<?php


function getConnected($mysqlDetails) {

	$db_host = $mysqlDetails['host'];
	$db_user = $mysqlDetails['user'];
	$db_pass = $mysqlDetails['pass'];
	$db_name = $mysqlDetails['DB'];

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

function createDatabase($link, $name, $user, $host, $pass) {

		$query[] = "CREATE DATABASE $name";
		$query[] = "GRANT SELECT, INSERT, UPDATE ON $name.* " .
					"TO '$user'@'$host' IDENTIFIED BY '$pass'";
		$query[] = "USE $name";
		$query[] = "CREATE TABLE products (name VARCHAR(40), description VARCHAR(140), " . 
					"cost DEC(6,2), price DEC(6,2), stock MEDIUMINT, code VARCHAR(20), " .
					"id INT UNSIGNED NOT NULL AUTO_INCREMENT, FULLTEXT(name), " .
					" INDEX(code), INDEX(id), PRIMARY KEY(id)) ENGINE MyISAM";

		foreach ($query as $q) {
			if($q == "USE $name;") {
				mysqli_select_db($link, $name) 
					or die("Unable to select database: " . mysqli_error($link));
			} else {
				$results[] = queryDatabase($link, $q);
			}
			
		}
		// Alert new user?

}

function queryDatabase($link, $query) {
	try {
		if(!$result = mysqli_query($link, $query)) {
			$error = mysqli_error($link);
			throw new Exception("<p>Failed on query: $query</p>" .
				"<p>Because: $error</p>");
		} 
	} catch (Exception $e) {
			echo ($e->getMessage());
	}
	return $result;
}

function bugger($msg) {
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>