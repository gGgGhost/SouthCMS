<?php

function getConnected($myLogin){

	$db_host = $myLogin['host'];
	$db_user = $myLogin['user'];
	$db_pass = $myLogin['pass'];
	$db_name = $myLogin['DB'];

	try {
		if (!$db_link = mysqli_connect($db_host, $db_user, $db_pass)){
			throw new Exception("Could not connect to MySQL because: " . mysqli_error($db_link));
		}
		else{
			bugger("<p>Connected to MySQL!</p>");
		}
	} catch (Exception $e){
		bugger($e->getMessage());
		// Do something about the failed connection
	}

	try {
		if (!$result = mysqli_select_db($db_link, $db_name)){
			throw new Exception("Could not select DB because: " . mysqli_error($db_link));
		}
		else{
			bugger("<p>Database selected!</p>");
		}
	} catch (Exception $e){
		bugger($e->getMessage());
		createDatabase($db_link, $db_name);
	}
		
	return $db_link;
}

function createDatabase($link, $name){

		$query[] = "CREATE DATABASE $name;";
		$query[] = "GRANT SELECT, INSERT, UPDATE ON test_database.* " .
					"TO 'test_user'@'localhost' IDENTIFIED BY 'test';";
		$query[] = "USE test_database;";
		$query[] = "CREATE TABLE test_table (name VARCHAR(25), price FLOAT, " . 
					"description CHAR(13), stock SMALLINT, code MEDIUMINT);";

		foreach ($query as $q) {
			if($q == "USE $name;"){
				mysqli_select_db($link, $name) 
					or die("Unable to select database: " . mysqli_error($link));
			}
			else{
				$results[] = mysqli_query($link, $q) 
					or die("Failed on query: $q. Because: " . mysqli_error($link));
			}
			
		}

		echo('<p>Database created, access granted for one user and a table created.</p>');

		mysqli_close($link)
			or die('Something went wrong closing the MySQL connection.');
}

function bugger($msg){
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>