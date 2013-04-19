<?php

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
				$results[] = queryDatabase($q, $link);
			}
			
		}
		// Alert new user?

}

function queryDatabase($query, $link) {
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

function retrieveUsingResult ($result, $link, $format = "array") {
	try {
			if (!$row = mysqli_fetch_assoc($result)) {
				$error = mysqli_error($link);
				throw new Exception("<p>Could not retrieve row.</p>" .
				"<p>$error</p>");
			} else {
				return $row;
			}			
		} catch (Exception $e) {
			bugger($e->getMessage());
		}
}

function bugger($msg) {
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>