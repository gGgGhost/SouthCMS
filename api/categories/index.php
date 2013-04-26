<?php
$directory = __DIR__;
require_once "$directory/../config.php";
require_once "$directory/../database.php";
require_once "$directory/../shared.php";

$method  	= 	$_SERVER['REQUEST_METHOD'];

$db_link = getConnected();

switch ($method) {
	case 'GET': 
		if (isset($_GET['all'])) {
			$all = retrieveVarFromGET('all', $db_link);
			if($all == true) {
				getListOfCategoryNames($db_link);
			}
		}
		break;
	case 'POST': 

		addCategoryFromPOST($db_link);
		break;
}

mysqli_close($db_link)
			or die('Something went wrong closing the MySQL connection.');

function getListOfCategoryNames($db_link, $format = 'array') {

	$query = "SELECT * FROM categories ORDER BY catname";
	$result = queryDatabase($query, $db_link);

	$stopHere = mysqli_num_rows($result);

	for ($i = 0; $i < $stopHere; $i++) {
		$row = retrieveUsingResult($result, $db_link);
		$names[] = $row['catname'];
	}
	return $names;
}

function addCategoryFromPOST($db_link){


if (isset($_POST['catname'])) {
	$name = retrieveVarFromPOST('catname', $db_link);
	
	$query = "INSERT INTO categories (catname) VALUES " .
			"('$name')";

	if ($results[] = queryDatabase($query, $db_link)) {
		
echo <<<END
<div class='addition'>
<a href="categories/?name=$name"><p class="top">Category Added: "$name"</p></a>
</div>
END;

	}
} else {
	echo("Variables not set correctly");
}

}

function countProductsInCategory($category, $db_link) {
	$query = "SELECT COUNT(*) FROM products, categories
			  WHERE products.catnum = categories.catnum
			  AND catname =  '$category'";
	$result = queryDatabase($query, $db_link);

	$number = retrieveUsingResult($result, $db_link);
	
	return $number['COUNT(*)'];
}

function countCategories($db_link) {
	$query = "SELECT COUNT(*) FROM categories";
	$result = queryDatabase($query, $db_link);

	$number = retrieveUsingResult($result, $db_link);
	
	return $number['COUNT(*)'];
}
 ?>