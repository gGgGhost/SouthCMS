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
			bugger("<p class='bugger'>Connected to MySQL!</p>");
		}
	} catch (Exception $e){
		bugger($e->getMessage());
		// Do something about the failed connection
	}
	
	try {
		if (!$result = mysqli_select_db($db_link, $db_name)){
			throw new Exception("<p class='bugger'>Could not select DB because it does not exist.</p>");
		}
		else{
			bugger("<p class='bugger'>Database selected!</p>");
		}
	} catch (Exception $e){
			bugger($e->getMessage());
			createDatabase($db_link, $db_name, $db_user, $db_host, $db_pass);
	} 
		
	return $db_link;
}

function createDatabase($link, $name, $user, $host, $pass){

		$query[] = "CREATE DATABASE $name;";
		$query[] = "GRANT SELECT, INSERT, UPDATE ON $name.* " .
					"TO '$user'@'$host' IDENTIFIED BY '$pass';";
		$query[] = "USE $name;";
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
		// Give the gubbins about starting out with the CMS
		echo('<p>Looks like you\'re new here!</p>');
		echo('<p>Everything is set up and ready for use.</p>');

		mysqli_close($link)
			or die('Something went wrong closing the MySQL connection.');
}

function selectDatabase($link, $name){

}

function bugger($msg){
	// Outputs a text string. 
	// Used for testing.
	// Comment out to stop debug messages.
	echo($msg);
}

?>