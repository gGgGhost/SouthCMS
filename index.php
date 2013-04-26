<?php
// Link into the api
$dir = __DIR__;
require_once "$dir/api/config.php";
require_once "$dir/api/page.php";
require_once "$dir/api/database.php";
require_once "$dir/api/shared.php";
$levelsDown = -1;
$db_link = getConnected();

// Set parameters
$pageTitle= $pageHeader = "SouthCMS";
$styles = getStyles($levelsDown);
$includeSearch = false;

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown);

$page['content'] = "<h3>Welcome to SouthCMS</h3>" .
					"<p>If you're seeing this page free of errors, " .
					"the database is installed and ready to go. Head" .
					" to <a href='cms'>the CMS page</a> to get started";

$page['end'] = "<script src='../api/js/ajax.js'></script>"
			 . "<script src='js/main.js'></script>"
			 . "<script src='../api/js/validate.js'></script>"
			 . preparePageEnd('');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>
