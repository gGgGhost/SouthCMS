<?php
// Link into the api
require_once "../api/products/index.php";
require_once "../api/page.php";

function prepareShopFront($numberToDisplay, $db_link){

	$levelsDown = 0;
	$styles = getStyles($levelsDown);
	$pageTitle = $pageHeader = "very simple shop";
	$includeSearch = true;

	$page['start'] = preparePageStart($pageTitle, $pageHeader, 
									  $styles, $includeSearch, $levelsDown);

	$page['content'] = "<div id='product_list'>";

	switch ($numberToDisplay) {
		case 0:
			$page['content'] =
				$page['content'] . 
				("<h2>There are no products in the store yet to display.</h2>");
			break;
		default: 
			$page['content'] = 
				$page['content'] . prepareProductList($numberToDisplay, $db_link);
			break;
	}

	$page['end'] = "</div>" . preparePageEnd();
		
	// Return this page array, containing 3 strings 
	// associated to keywords 'start', 'content', and 'end'
	return $page;
}

// Hook up to database
$db_link = getConnected();

// Get the numbers
$totalProducts = countProducts($db_link);
$numberToDisplay = 6;

// Reduce number if not enough products in DB to meet the desired amount
if ($numberToDisplay > $totalProducts) { $numberToDisplay = $totalProducts; }

// Get page string for this product, formatted for the shop
$page = prepareShopFront($numberToDisplay, $db_link);

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link);

?>