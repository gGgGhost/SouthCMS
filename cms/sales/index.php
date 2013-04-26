<?php
// Link into the api
require_once "../../api/sales/index.php";
require_once "../../api/products/index.php";
require_once "../../api/page.php";

// Hook up to database
$db_link = getConnected();


$levelsDown = 1;
$pageHeader = "SouthCMS";
$pageTitle = 'Sales - ' . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown);

$page['content'] = "<h2>Sales</h2>" .
				"<div id='sales'>";
$page['content'] .= getSales($db_link);


$page['end'] = "</div>" . preparePageEnd('cms');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>