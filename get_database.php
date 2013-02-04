<?php // Get link to database through MySQL
require_once 'login.php';
$db_host = $db_login['hostname'];
$db_username = $db_login['username'];
$db_password = $db_login['password'];
$db_name = $db_login['database'];

function getDbLink(){

	$db_link = mysqli_connect($db_host, $db_username, $db_password) 
		or die('Could not connect to MySQL: ' . mysqli_error());

	mysqli_select_db($db_link, $db_name)
		or die('Could not select DB: ' . mysqli_error());
		
	return $db_link;
}

?>