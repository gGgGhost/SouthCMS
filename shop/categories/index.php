<?php
// Link into the api
require_once "../../api/categories/index.php";
require_once "../../api/products/index.php";
require_once "../../api/page.php";

// Hook up to database
$db_link = getConnected();

$category = retrieveVarFromGET('name', $db_link);

// Get the numbers
$totalProducts = countProductsInCategory($category, $db_link);
$productsPerPage = 6;

if ($productsPerPage > $totalProducts) {
	$productsPerPage = $totalProducts;
}
$totalPages = ceil($totalProducts / $productsPerPage);


if (isset($_GET['page'])) {
	$currentPage = $_GET['page'];
} else {
	$currentPage = 1;
}

$offset = ($currentPage * $productsPerPage) - $productsPerPage;

$levelsDown = 1;
$styles = getStyles($levelsDown);
$pageHeader = "very simple shop";
$pageTitle = $category . ' - ' . $pageHeader;
$includeSearch = false;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "<h2>$category</h2>" .
				"<div id='product_list'>";

switch ($productsPerPage) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in this category.</h2>");
		break;
	default: 
		$page['content'] .= prepareProductsList($productsPerPage, $offset,
											 $db_link, $category, $levelsDown);
		$page['content'] .= "<div id='page_links'>Page" . 
					getPageLinks($currentPage, $totalPages, $category) .
					"</div>";
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