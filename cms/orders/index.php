<?php // Link into the api
$directory = __DIR__;
require_once "$directory/../../api/products/index.php";
require_once "$directory/../../api/page.php";


// Hook up to database
$db_link = getConnected();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['id'])) {
			$id = retrieveVarFromPOST('id', $db_link);
			$method = 'POST';
		}
}

// Set parameters
$levelsDown = 1;
$pageHeader = "SouthCMS";
$pageTitle = "Orders - " . $pageHeader;
$styles = getStyles($levelsDown);
$includeSearch = false;

if (isset($id)) {
	$query = "SELECT prodnum, quantity FROM in_order WHERE order_id='$id'";
	$result = queryDatabase($query, $db_link);
	$num = mysqli_num_rows($result);
	for ($i = 0; $i < $num; $i++) {
		$row = retrieveUsingResult($result, $db_link);
		$products[] = $row;
		$prodnum = $row['prodnum'];
		$queries[] = "SELECT prodnum, stock FROM products WHERE prodnum='$prodnum'";
	}
	for ($i = 0; $i < count($query); $i++) {
		$result = queryDatabase($queries[$i], $db_link);
		$row = retrieveUsingResult($result, $db_link);
		$stock[] = $row['stock'];
	}
	$checkStock = "";
	for ($i = 0; $i < count($stock); $i++) {
		$qty = $products[$i]['quantity'];
		$stk = $stock[$i];
		if($qty > $stk) {
			$checkStock .= "imbalance";
		}
	} 
	if ($checkStock == "") {
		for ($i = 0; $i < count($products); $i++) {
		$prodnum = $products[$i]['prodnum'];
		$quantity = $products[$i]['quantity'];
		$query = "UPDATE products SET stock=stock-$quantity WHERE prodnum='$prodnum'";
		$result = queryDatabase($query, $db_link);
		}
		markOrderComplete($id, $db_link);
		echo("<h3>Order ID $id Marked Complete.</h3>");
		echo(orderTables($db_link) . "</div>");
	} else {
		echo("<h3>Cannot submit order. Not enough stock.</h3><a href=''>back</a>");
	}
	
}

else {
$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								$styles, $includeSearch, $levelsDown);
$page['content'] = "<div id='order_area'>";

$page['content'] .= orderTables($db_link);

$page['content'] .= "</div>";

$page['end'] = "<script src='../../api/js/ajax.js'></script>"
			 . "<script src='../js/orders.js'></script>"
			 . preparePageEnd('cms');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);
}
// End connection
mysqli_close($db_link); 

function orderTables ($db_link) {
	$tables = "<h2>Outstanding orders:</h2>";
	$tables .= prepareOrderTable('FALSE', $db_link);
	$tables .= "<h2>Completed orders:</h2>";
	$tables .= prepareOrderTable('TRUE', $db_link);
	return $tables;
}

function markOrderComplete($id, $db_link) {
	$query = "UPDATE orders SET completed=TRUE WHERE order_id='$id'";
	$result = queryDatabase($query, $db_link);
}
function howManyOrders($completed, $db_link) {
	$query = "SELECT COUNT(*) FROM orders WHERE completed=$completed";
	$result = queryDatabase($query, $db_link);
	$amount = retrieveUsingResult($result, $db_link)['COUNT(*)'];
	return $amount;
}

?>