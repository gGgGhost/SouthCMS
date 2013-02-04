<?php

$db_link = mysqli_connect("localhost","root","") 
	or die('Could not connect to MySQL: ' . mysqli_error());

if($db_link){
	
	$query = "DROP DATABASE test_database;";
	$result = mysqli_query($db_link, $query) or die("<p>The database doesn't exist</p>");

	if($result){
		echo('<p>The database was dropped successfully.</p>');
	}

	mysqli_close($db_link)
		or die('Something went wrong closing the MySQL connection.');
}

?>