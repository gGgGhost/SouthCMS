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
$properties['page_header'] = "very simple shop";
$properties['button_id'] = "buy";
$properties['button_value'] = "Add to basket";
$properties['include_search'] = true;

// set the sections to be displayed
$section['name'] = "description";
$section['showHeading'] = true;
$sections[] = $section;
$section['name'] = "catname";
$section['showHeading'] = true;
$sections[] = $section;
$section['name'] = "price";
$section['showHeading'] = false;
$sections[] = $section;
$section['name'] = "stock";
$section['showHeading'] = false;
$sections[] = $section;

// Get page string for this product
$page = prepareProductPage($product, $sections, $properties);

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>