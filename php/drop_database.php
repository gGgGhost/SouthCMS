<?php
$dblink = mysqli_connect("localhost","root","") or die('Could not connect to MySQL: ' . mysqli_error());
if($dblink){
	echo("<p>The link has been established.</p>");
}

$query = "DROP DATABASE test_database;";
$result = mysqli_query($dblink, $query) or die("<p>The database doesn't exist</p>");
if($result){
	echo("<p>The database was dropped successfully.</p>");
}


?>