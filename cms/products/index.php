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
$pageHeader = "SouthCMS";
$pageTitle = $productName . " - " . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								$styles, $includeSearch, $levelsDown);

$buttonId = "edit";
$buttonValue = "Edit this product";

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

// Open product_area tag, add header with product name/title
$page['content'] = 
	"<div id='product_area'>
	<h2 id='product_name'>$productName</h2>";

// Add a line for each section of requested detail relating to this product 
$page['content'] = $page['content'] . prepareProductDetails($sections, $product);

// Add button, close product_area tag
$page['content'] = $page['content'] .
	"<button id='$buttonId'>$buttonValue</button>
	</div>";

$page['end'] = preparePageEnd('cms');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>