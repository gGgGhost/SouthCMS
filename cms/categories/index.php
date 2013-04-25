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
$productsPerPage = 25;

if (isset($_GET['page'])) {
	$currentPage = $_GET['page'];
} else {
	$currentPage = 1;
}
$totalPages = ceil($totalProducts / $productsPerPage);
$offset = ($currentPage * $productsPerPage) - $productsPerPage;

$levelsDown = 1;
$format = 'table';
$pageHeader = "SouthCMS";#
$pageTitle = $category . ' - ' . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown);

$page['content'] = "<h2>$category</h2>" .
				"<div id='product_list'>";

switch ($productsPerPage) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in this category.</h2>");
		break;
	default: 
		$page['content'] = 
			$page['content'] . prepareProductsTable($productsPerPage, $offset,
											 $db_link, $category, $levelsDown);
		break;
}

$page['content'] = $page['content'] . "<div id='page_links'>Page" . 
					getPageLinks($currentPage, $totalPages, $category) .
					"</div>";

$page['end'] = "</div>" . preparePageEnd('cms');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>