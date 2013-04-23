<?php // Link into the api
$directory = __DIR__;
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";

// Hook up to database
$db_link = getConnected();

// Get the desired product, based on GET parameter
$id = $_GET['id'];
$product = getProduct($id, $db_link);
$productName = $product['name'];

// Set parameters
$levelsDown = 1;
$pageHeader = "very simple shop";
$pageTitle = $productName . " - " . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
									$styles, $includeSearch, $levelsDown, $basket);

$buttonId = "buy";
$buttonValue = "Add 1 to basket";

// set the sections to be displayed
$section['name'] = "description";
$section['showHeading'] = true;
$sections[] = $section;
$section['name'] = "catname";
$section['showHeading'] = true;
$sections[] = $section;
$section['name'] = "price";
$section['showHeading'] = false;
$section['prefix'] = "&pound;";
$sections[] = $section;
$section['name'] = "stock";
$section['showHeading'] = false;
$section['prefix'] = "";
$sections[] = $section;

// Open product_area tag, add header with product name/title
$page['content'] = "<div id='product_area'>" 
				 . "<h2 id='product_name'>$productName</h2>";

// Add a line for each section of requested detail relating to this product 
$page['content'] = $page['content'] . prepareProductDetails($sections, $product);

// Add button, close product_area tag
$page['content'] = $page['content'] 
				 . "<button id='$buttonId'>$buttonValue</button></div>"
				 . "<p id='added'></p>"
				 . "<script src='../js/basket.js'></script>"
				 . "<script src='../js/shop.js'></script>";

$page['end'] = preparePageEnd();

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>