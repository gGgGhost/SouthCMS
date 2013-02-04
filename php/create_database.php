<?php
$dblink = mysqli_connect("localhost","root","") or die('Could not connect to MySQL: ' . mysqli_error());
if($dblink){
	echo("<p>The link has been established.</p>");
}

$query = "CREATE DATABASE test_database;";
$result = mysqli_query($dblink, $query) or die("<p>The database already exists</p>");
if($result){
	echo("<p>The database was created successfully.</p>");
}
if(msql_close($dblink)){
	echo"<p>The link has been severed.</p>";
}
?>