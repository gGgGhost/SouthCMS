<?php
require_once 'login.php';
$db_host = $db_login['hostname'];
$db_username = 'root';
$db_password = '';
$db_name = $db_login['database'];

$db_link = mysqli_connect($db_host, $db_username, $db_password) 
			or die('Could not connect to MySQL: ' . mysqli_error());

if($db_link){

	$query[] = "CREATE DATABASE $db_name;";
	$query[] = "GRANT SELECT, INSERT, UPDATE ON test_database.* " .
				"TO 'test_user'@'localhost' IDENTIFIED BY 'test';";
	$query[] = "USE test_database;";
	$query[] = "CREATE TABLE test_table (name VARCHAR(25), price FLOAT, " . 
				"description CHAR(13), stock SMALLINT, code MEDIUMINT);";

	foreach ($query as $q) {
		if($q == "USE $db_name;"){
			mysqli_select_db($db_link, $db_name) 
				or die("Unable to select database: " . mysqli_error());
		}
		else{
			$results[] = mysqli_query($db_link, $q) 
				or die("Failed on query: $q");
		}
		
	}

	echo('<p>Database created, access granted for one user and a table created.</p>');

	mysqli_close($db_link)
		or die('Something went wrong closing the MySQL connection.');

}
?>