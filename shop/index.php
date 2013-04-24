<?php
// Link into the api
require_once "../api/products/index.php";
require_once "../api/page.php";

// Hook up to database
$db_link = getConnected();

// Get the numbers
$totalProducts = countProducts($db_link);
$productsPerPage = 6;

// Reduce number if not enough products in DB to meet the desired amount
if ($productsPerPage > $totalProducts) { $productsPerPage = $totalProducts; }

if (isset($_GET['page'])) {
	$currentPage = $_GET['page'];
} else {
	$currentPage = 1;
}
$totalPages = ceil($totalProducts / $productsPerPage);
$offset = ($currentPage * $productsPerPage) - $productsPerPage;

$levelsDown = 0;
$styles = getStyles($levelsDown);
$pageTitle = $pageHeader = "very simple shop";
$includeSearch = true;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "<div id='product_list'>";

switch ($productsPerPage) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in the store yet to display.</h2>");
		break;
	default: 
		$page['content'] = 
			$page['content'] . prepareProductList($productsPerPage, $offset, $db_link);
		break;
}

$page['content'] = $page['content'] . "<div id='page_links'>Page" . 
					getPageLinks($currentPage, $totalPages) .
					"</div>";

$page['end'] = "</div>" 
			 . "<script src='js/basket.js'></script>"
			 . preparePageEnd('shop', '');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>