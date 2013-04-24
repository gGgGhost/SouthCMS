<?php
// Link into the api
require_once "../../api/products/index.php";
require_once "../../api/page.php";

// Hook up to database
$db_link = getConnected();


$levelsDown = 1;
$styles = getStyles($levelsDown);
$pageHeader = "very simple shop";
$pageTitle = "Basket - " . $pageHeader;
$includeSearch = true;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "<div id='basket_contents'><h2>Products in basket</h3>"
		. "<div id='basket_contents'></div>";

$page['end'] = "</div>" 
			 . "<script src='../js/basket.js'></script>"
 			 . "<script src='../js/order.js'></script>"
			 . preparePageEnd('shop');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>