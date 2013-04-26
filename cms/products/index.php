<?php // Link into the api
$directory = __DIR__;
require_once "$directory/../../api/categories/index.php";
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";

// Hook up to database
$db_link = getConnected();
// Set parameters
$levelsDown = 1;
$pageHeader = "SouthCMS";
$styles = getStyles($levelsDown);
$includeSearch = false;
$buttonId = "edit";
$buttonValue = "Edit this product";

// Get the desired product, based on GET parameter
if(isset($_GET['id'])) {
	$id = $_GET['id'];
	$product = getProduct($id, $db_link);
	$productName = $product['name'];
	$pageTitle = $productName . " - " . $pageHeader;
} else {
	$totalProducts = countProducts($db_link);
	$productsPerPage = 25;
	$pageTitle = $pageHeader;
	if (isset($_GET['page'])) {
		$currentPage = $_GET['page'];
	} else {
		$currentPage = 1;
	}
	$totalPages = ceil($totalProducts / $productsPerPage);
	$offset = ($currentPage * $productsPerPage) - $productsPerPage;
}

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								$styles, $includeSearch, $levelsDown);

// Open product_area tag, add header with product name/title
$page['content'] = "<div id='product_area'>";
	
if (isset($id)) {
	// set the sections to be displayed
	$productByNumber = array_keys($product);
	for ($i = 0; $i < count($product); $i++) {
		if ($i == 0) {continue;}
		$section['name'] = $productByNumber[$i];
		$section['showHeading'] = true;
		if ($section['name'] == 'price' || $section['name'] == 'cost') {
			$section['prefix'] = "&pound;";
		}
		else {
			$section['prefix'] = "";
		}
		$sections[] = $section;
	}
	$page['content'] .= "<h2 id='product_name'>$productName</h2>";
}	

// Add a line for each section of requested detail relating to this product 
if (isset($id)) {
	$page['content'] .= prepareProductDetails($sections, $product);
	// Add button, close product_area tag
	$page['content'] .= "<button id='$buttonId'>$buttonValue</button>";
} else {
	switch ($totalProducts) {
	case 0:
		$page['content'] =
			$page['content'] . 
			("<h2>There are no products in the store yet to display.</h2>");
		break;
	default: 
		$page['content'] .= prepareProductsTable($productsPerPage, 
								$offset, $db_link, 'all', $levelsDown);
		$page['content'] .= "<div id='page_links'>Page" . 
					getPageLinks($currentPage, $totalPages) .
					"</div>";
		break;
}
}


$page['content'] .= "</div>";

$page['end'] = preparePageEnd('cms');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>