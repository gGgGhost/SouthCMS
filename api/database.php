<?php

function getConnected($mysqlDetails){

	$db_host = $mysqlDetails['host'];
	$db_user = $mysqlDetails['user'];
	$db_pass = $mysqlDetails['pass'];
	$db_name = $mysqlDetails['DB'];

	try {
		if (!$db_link = mysqli_connect($db_host, $db_user, $db_pass)){
			throw new Exception("Could not connect to MySQL because: " . mysqli_error($db_link));
		}
	} catch (Exception $e){
		bugger($e->getMessage());
		// Do something about the failed connection
	}
	
	try {
		if (!$result = mysqli_select_db($db_link, $db_name)){
			throw new Exception("<p class='bugger'>Could not select DB because it does not exist.</p>");
		}
	} catch (Exception $e){
			bugger($e->getMessage());
			createDatabase($db_link, $db_name, $db_user, $db_host, $db_pass);
	} 
		
	return $db_link;
}

function createDatabase($link, $name, $user, $host, $pass){

		$query[] = "CREATE DATABASE $name";
		$query[] = "GRANT SELECT, INSERT, UPDATE ON $name.* " .
					"TO '$user'@'$host' IDENTIFIED BY '$pass'";
		$query[] = "USE $name";
		$query[] = "CREATE TABLE products (name VARCHAR(25), description VARCHAR(140), " . 
					"cost DEC(4,2), price DEC(4,2), stock MEDIUMINT, code VARCHAR(25), " .
					"id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY)";

		foreach ($query as $q) {
			if($q == "USE $name;"){
				mysqli_select_db($link, $name) 
					or die("Unable to select database: " . mysqli_error($link));
			}
			else{
				$results[] = queryDatabase($link, $q);
			}
			
		}
		// Give the gubbins about starting out with the CMS
		echo('<p>Looks like you\'re new here!</p>');
		echo('<p>Everything is set up and ready for use.</p>');

		mysqli_close($link)
			or die('Something went wrong closing the MySQL connection.');
}

function queryDatabase($link, $query){
	$result = mysqli_query($link, $query)
				or die("<p>Failed on query: $query.</p><p>Because: " .
					mysqli_error($link) . ".</p>");
	return $result;
}

function bugger($msg){
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>