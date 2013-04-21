<?php // Link into the api
$directory = __DIR__;
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";

// Hook up to database
$db_link = getConnected();

// Get the desired product, based on GET parameter
$id = $_GET['id'];
$product = getProduct($id, $db_link);

// set the properties
$properties['page_header'] = "SouthCMS";
$properties['button_id'] = "edit";
$properties['button_value'] = "Edit this product";
$properties['include_search'] = false;

// set the sections to be displayed
$productByNumber = array_keys($product);
for ($i = 0; $i < count($product); $i++) {
	if ($i == 0) {continue;}
	$section['name'] = $productByNumber[$i];
	$section['showHeading'] = true;
	$sections[] = $section;
}

// Get page string for this product
$page = prepareProductPage($product, $sections, $properties);

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>