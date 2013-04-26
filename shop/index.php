<?php
// Link into the api
require_once "../api/categories/index.php";
require_once "../api/products/index.php";
require_once "../api/page.php";

// Hook up to database
$db_link = getConnected();

$optionList = "";
$numberOfCategories = countCategories($db_link);
if ($numberOfCategories > 0) {
	$categoryNames = getListOfCategoryNames($db_link);
	$cats = "";
	for ($i = 0; $i < count($categoryNames); $i++) {
		$name = $categoryNames[$i];
		$href = "categories/?name=$name";
		$line = "<div class='cat'>$name</div>";
		$cats .= wrapWithLink($href, $line);
	} 
	$cats = '<div id="cat_box">View: ' . $cats . '</div>';
}
else {
	$cats = "";
}

// Get the numbers
$totalProducts = countProducts($db_link);
$productsPerPage = 6;

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
$includeSearch = false;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "$cats<div id='product_list'>";

switch ($totalProducts) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in the store yet to display.</h2>");
		break;
	default: 
		$page['content'] = 
			$page['content'] . prepareProductsList($productsPerPage, $offset, $db_link);
		$page['content'] = $page['content'] . "<div id='page_links'>Page" . 
					getPageLinks($currentPage, $totalPages) .
					"</div>";
		break;
}



$page['end'] = "</div>" 
			 . "<script src='js/basket.js'></script>"
			 . preparePageEnd('shop', '');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>