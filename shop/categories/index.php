<?php
// Link into the api
require_once "../../api/categories/index.php";
require_once "../../api/products/index.php";
require_once "../../api/page.php";

// Hook up to database
$db_link = getConnected();

$category = retrieveVarFromGET('name', $db_link);

// Get the numbers
$totalProductsInCategory = countProductsInCategory($category, $db_link);
$numberToDisplay = 6;

// Reduce number if not enough products in DB to meet the desired amount
if ($numberToDisplay > $totalProductsInCategory) { $numberToDisplay = 
												   $totalProductsInCategory; }

$levelsDown = 1;
$styles = getStyles($levelsDown);
$pageHeader = "very simple shop";
$pageTitle = $category . ' - ' . $pageHeader;
$includeSearch = true;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "<h2>$category</h2>" .
				"<div id='product_list'>";

switch ($numberToDisplay) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in this category.</h2>");
		break;
	default: 
		$page['content'] = 
			$page['content'] . prepareProductList($numberToDisplay, $db_link, $category, $levelsDown);
		break;
}

$page['end'] = "</div>"
			 . "<script src='../js/basket.js'></script>"
			 . preparePageEnd('shop');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>