<?php // Link into the api
$directory = __DIR__;
require_once "$directory/../../api/orders/index.php";
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";

// Hook up to database
$db_link = getConnected();
// Set parameters
$levelsDown = 1;
$pageHeader = "SouthCMS";
$pageTitle = "Orders - " . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;




$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								$styles, $includeSearch, $levelsDown);
$page['content'] = "<div id='order_area'>";

$page['content'] .= "<h2>Outstanding orders:</h2>" . prepareOrderTable('FALSE', $db_link);

$page['content'] .= "<h2>Completed orders:</h2>" . prepareOrderTable('TRUE', $db_link);

$page['content'] .= "</div>";

$page['end'] = preparePageEnd('cms');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>