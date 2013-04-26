<?php
require_once "config.php";
require_once "database.php";

function buildBigButton ($button) {
	$id = $button['id'];
	$img = $button['img'];
	$captionHead = $button['captionHead'];
	$caption = $button['caption'];
	$buttonString =
	"<figure class='big_button option' id='$id'>
		<img src='images/icons/128x128/$img' class='icon-biggest' />
		<figcaption>
			<h1 class='big_button-caption'>$captionHead</h1>
			<p>$caption</p>
		</figcaption>
	</figure>";
	return $buttonString;
}
function prepareOrderTable($completed, $db_link) {
	$amount = howManyOrders($completed, $db_link);
	if ($amount > 0){
		if ($completed == 'FALSE') {
			$col = "<th>complete</th>";
		}
		else {
			$col = "";
		}
		$orderTable = "<table><tr><th>order id</th><th>prod + qty.</th>"
						. "<th>fullname</th><th>address</th><th>postcode</th>"
						. "<th>email</th>$col</tr>";
		$query = "SELECT order_id, fullname, address, postcode, email" .
					" FROM orders, customers WHERE orders.cust_id=customers.cust_id"
					." AND completed=$completed";
		$result = queryDatabase($query, $db_link);
		$stopHere = mysqli_num_rows($result);

		for ($i = 0; $i < $stopHere; $i++) {
				$orders[] = retrieveUsingResult($result, $db_link, "orders");
				$ids[] = $orders[$i]['order_id'];
		}

		for ($i = 0; $i < count($ids); $i++) {
			$tableLine = "";
			$sections = [];
			$products = [];
			$order = $orders[$i];
			$id = $ids[$i];

			$query = "SELECT prodnum, quantity FROM in_order WHERE order_id='$id'";
			$result = queryDatabase($query, $db_link);
			$stopHere = mysqli_num_rows($result);
			for ($c = 0; $c < $stopHere; $c++) {
				$row = retrieveUsingResult($result, $db_link, "products");
				$products[] = $row;
			}
			
			$sections[] = $order['fullname'];
			$sections[] = $order['address'];
			$sections[] = $order['postcode'];
			$sections[] = $order['email'];
			$tableLine = "<tr><td>$id</td><td>";
			for ($c = 0; $c < count($products); $c++) {
				$product = $products[$c];
				$num = $product['prodnum'];
				$qty = $product['quantity'];
				$prod = wrapWithLink("../products/?id=$num", $num);
				$tableLine .= "<p>$prod x $qty</p>";
			}
			$tableLine .= "</td>";
			for ($c = 0; $c < count($sections); $c++) {
				$thisSection = $sections[$c];
				$tableLine .= "<td>$thisSection</td>";
			}
			if ($completed == 'FALSE') {
				$tableLine .= "<td><button id='bt_$id' class='complete'>"
						. "confirm</button></tr>";
			}
			$orderTable .= $tableLine;
		}
		$orderTable .= "</table>";
	} else {
		$orderTable = "<h3>None</h3>";
	}
	return $orderTable;

}
	

function prepareProductDetails ($sections, 
								$product, $format = 'list') {
	$lines = "";
	for ($i = 0; $i < count($sections); $i++) {
		$lines = $lines . getProductSection($sections[$i], $product, $format);
	}
	return $lines;
}

function getProductSection ($sectionDetails, 
							$product, $format, $tag = "id", $dir = "../") {

	$sectionName = $sectionDetails['name'];
	$datum = $product[$sectionName];
	$thisSegment = $datum;
	$showHeading = $sectionDetails['showHeading'];


	if ($sectionName == 'catname') {
		$link = $dir . "categories/?name=$datum";
		$thisSegment = wrapWithLink($link, $thisSegment);
	}

	if ($sectionName == 'stock') {
		$thisSegment = isThereEnoughStock($datum);
	}

	if (isset($sectionDetails['prefix'])) {
		$prefix = $sectionDetails['prefix'];
		$thisSegment = $prefix . $thisSegment;
	}
	
	if (isset($sectionDetails['hide'])) {
		$hide = " class='empty'";
	} else {
			$hide = "";
	}
	

	$section = "";

	switch ($format) {
		case 'list':
			if ($showHeading == true) {
				if ($sectionName =='catname') {$sectionName='category';}
				$section = $section . "<h3>$sectionName</h3>";
			}
			$section = $section . "<p$hide $tag='product_$sectionName'>$thisSegment</p>";
			break;
		case 'table':
			$section = $section . "<td>$thisSegment</td>";
			break;
	}
	return $section;
}


function prepareProductsList ($limit,
							 $offset, 
							 $db_link,
							 $category = 'all', 
							 $levelsDown = 0) {

	$products = getManyProducts($limit, $offset, $db_link, $category);
	$tag ="class";
	$format = 'list';
	$list = '';
			
	if ($category == 'all') {
		$section['name'] = "catname";
		$section['showHeading'] = false;
		$section['prefix'] = " . ";
		$sections[] = $section;
	}

	$section['name'] = "stock";
	$section['showHeading'] = false;
	$section['prefix'] = "";
	$sections[] = $section;

	$section['name'] = "price";
	$section['showHeading'] = false;
	$section['prefix'] = "&pound;";
	$sections[] = $section;

	$numberOfSections = count($sections);
	
	switch ($levelsDown) {
		case 0:
			$dir = "";
			break;
		default:
			$dir = "../";
			break;
	}

	for ($i = 0; $i < count($products); $i++) {
		$product = $products[$i];
		$name = $product['name'];
		$id = $product['prodnum'];
		$list .=
			"<div class='product'>
			<h2 $tag='product_name'><a href='" . $dir . 
			"products/?id=$id'>$name</a></h2>";

		// Add a line for each section of requested detail relating to this product 
		for ($c = 0; $c < $numberOfSections; $c++) {
			$list .= getProductSection($sections[$c], $product, $format, $tag, $dir);
		}
		
		$list .= "</div>";
	}

	return $list;
}

function PrepareProductsTable ($limit,
							 $offset, 
							 $db_link,
							 $category = 'all', 
							 $levelsDown = 0) {

	$products = getManyProducts($limit, $offset, $db_link, $category);
	$tag ="class";
	$format = 'table';
	$headings = '<tr><th></th><th></th><th>cost</th>'
			  . '<th>price</th><th>code</th><th>id</th></tr>';
	$list = "<table>$headings";
	

	$section['name'] = "stock";
	$section['showHeading'] = false;
	$sections[] = $section;

	$section['name'] = "cost";
	$section['showHeading'] = false;
	$section['prefix'] = "&pound;";
	$sections[] = $section;

	$section['name'] = "price";
	$section['showHeading'] = false;
	$sections[] = $section;
	unset($section['prefix']);

	$section['name'] = "code";
	$section['showHeading'] = false;
	$sections[] = $section;

	$section['name'] = "prodnum";
	$section['showHeading'] = false;
	$sections[] = $section;


	$numberOfSections = count($sections);
	
	switch ($levelsDown) {
		case 0:
			$dir = "";
			break;
		default:
			$dir = "../";
			break;
	}

	for ($i = 0; $i < count($products); $i++) {
		$product = $products[$i];
		$name = $product['name'];
		$id = $product['prodnum'];
		$list .=
			"<tr class='product'>
			<td $tag='product_name'><a href='" . $dir . 
			"products/?id=$id'>$name</a></td>";

		// Add a line for each section of requested detail relating to this product 
		for ($c = 0; $c < $numberOfSections; $c++) {
			$list .= getProductSection($sections[$c], $product, $format, $tag, $dir);
		}
	}
	$list .= '</table>';
	return $list;
}

function getPageLinks ($currentPage, $totalPages, $category = "") {
	$links = " ";
	if ($category != "") {
		$cat = "name=$category&";
	} else {
			$cat = "";
	}

	for ($i = 1; $i <= $totalPages; $i ++) {
		$thisLink = "<a href='?$cat" . "page=$i'>$i</a>";
		if ($i == $currentPage && $totalPages > 1) {
			$thisLink = "<span id='current_page_marker'>" .
						$thisLink . "</span>";
		}
		$thisLink = $thisLink . " ";
		$links = $links . $thisLink;
	}
	return $links;
}

function preparePageStart ($title, 
						   $header, 
						   $styles, 
						   $includeSearch, 
						   $levelsDown = 1, $extra = "") {

	// Make sure main link points home,
	// depending on place in structure
	switch ($levelsDown) {
		case 0:
			$homeLink = "";
			break;
		case -1:
			$homeLink = "cms/";
			break;
		default:
			$homeLink = "..";
			break;
	}
	if ($homeLink != "") {
		$prefix = $homeLink . "/";
	}
	else {
		$prefix = $homeLink;
	}
	$basketLink = $prefix . "basket";
	$basket = "<div id='basket'></div>";
	$basket = wrapWithLink($basketLink, $basket);
	// Declare doctype
	// Open html tag
	// Open body tag
	// Add relevant title and header,
	// including styles and home page link
	// Open main_section tag
	$top = 
		"<!DOCTYPE html>
		<html>
		<head>
			<title>$title</title>
			$styles
		</head>
		<body>
		<header>
			<h1><a href='$homeLink'>$header</a></h1>
			$basket
		</header>
		<div id='main_section'>";

	// Add a search bar if requested
	if ($includeSearch == true) {
		$top = $top .
			"<form id='search_bar'>
				<label for='search_box'>Search:</label>
				<input type='search' name='search_box' /><input type='submit' value='Go' />
			</form>";
	}

	return $top;
}

function prepareCategoryOptionList ($values, 
						   		    $quantity) {
	$list = "";
	for ($i = 0; $i < $quantity; $i++) {
		$name = $values[$i];
		$list = $list . "<option value='$name'>$name</option>";
	}
	return $list;
}

function preparePageEnd ($currentZone, $dir = "../") {
	// Close 'main_section', 'body' and 'html' tags
	// Open and close footer
	

	if ($currentZone == "shop") {
		$link = $dir . "../cms"; 
		$newZone = "southcms";
		$link = "<a href='$link'>$newZone</a>";
	}
	else if ($currentZone == "cms") {
		$link = $dir . "../shop"; 
		$newZone = "shop";
		$link = "<a href='$link'>$newZone</a>";
	}
	else {
		$link = "";
	}
	
	
	$bottom =
		"</div>
		<footer>
		$link
		</footer>
		</body>
		</html>";

	return $bottom;
}

function getStyles ($levelsDown) {

	$hrefs[] = "api/style.css";
	$hrefs[] = "style.css";
	$numberOfStyles = count($hrefs);
	$styles = "";
	$numberOfPrefixes = ($levelsDown + $numberOfStyles) - 1;

	for ($i = 0; $i < $numberOfStyles; $i++) {
		$prefix = "";
		
		for ($c = 0; $c < $numberOfPrefixes; $c++) {
			$prefix = $prefix . "../";
		}
		$numberOfPrefixes --;
		$href = $prefix . $hrefs[$i];
		$styles = $styles . "<link href='$href' type='text/css' rel='stylesheet' />";
	}
	
	return $styles;
}
?>