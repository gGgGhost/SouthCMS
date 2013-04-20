<?php
require_once "config.php";
require_once "database.php";

function prepareProductPage ($typeOfPage, $product) {

	$levelsDown = 1;
	$styles = getStyles($levelsDown);
	$productName = $product['name'];

	switch ($typeOfPage) {
		case 'cms':
					$pageHeader = "SouthCMS";
					$productByNumber = array_keys($product);
					for ($i = 0; $i < count($product); $i++) {
						if ($i == 0) {continue;}
						$section['name'] = $productByNumber[$i];
						$section['showHeading'] = true;
						$sections[] = $section;
					}

					$buttonId = "edit";
					$buttonValue = "Edit this product";
					$includeSearch = false;
					break;
		case 'shop':
					$pageHeader = "very simple shop";

					$section['name'] = "description";
					$section['showHeading'] = true;
					$sections[] = $section;

					$section['name'] = "price";
					$section['showHeading'] = false;
					$sections[] = $section;

					$section['name'] = "stock";
					$section['showHeading'] = false;
					$sections[] = $section;

					$buttonId = "buy";
					$buttonValue = "Add to basket";
					$includeSearch = true;
					break;
	}
	$pageTitle = $productName . " - " . $pageHeader;
	// Add the top chunk of the html page
	$page['start'] = preparePageStart($pageTitle, $pageHeader, $styles, $includeSearch);

	// Open product_area tag, add header with product name/title
	$page['content'] = 
		"<div id='product_area'>
		<h2 id='product_name'>$productName</h2>";

	// Add a line for each section of requested detail relating to this product 
	for ($i = 0; $i < count($sections); $i++) {
		$page['content'] = $page['content'] . thisProductLine($sections[$i], $product);
	}

	// Add button, close product_area tag
	$page['content'] = $page['content'] .
		"<button id='$buttonId'>$buttonValue</button>
		</div>";
	
	$page['end'] = preparePageEnd();
		
	// Return this page array, containing 3 strings 
	// associated to keywords 'start', 'content', and 'end'
	return $page;
}

function prepareShopFront($numberToDisplay, $db_link){

	$levelsDown = 0;
	$styles = getStyles($levelsDown);
	$pageTitle = $pageHeader = "very simple shop";
	$includeSearch = true;

	$page['start'] = preparePageStart($pageTitle, $pageHeader, $styles, $includeSearch);

	$page['content'] = "<div id='product_list'>";

	switch ($numberToDisplay) {
		case 0:
			$page['content'] =
				$page['content'] . 
				("<h2>There are no products in the store yet to display.</h2>");
			break;
		default: 
			$page['content'] = 
				$page['content'] . prepareProductList($numberToDisplay, $db_link);
			break;
	}

	$page['end'] = "</div>" . preparePageEnd();
		
	// Return this page array, containing 3 strings 
	// associated to keywords 'start', 'content', and 'end'
	return $page;
}
// To do
/*
function prepareCMS(){}
function prepareInputForm(){}
function prepareFormSegment(){}
*/
function thisProductLine ($section, $product) {

	$name = $section['name'];
	$datum = $product[$name];
	$showHeading = $section['showHeading'];

	if ($name == 'price' || $name == 'cost') {
		$prefix = "&pound;";
	}
	else{
		$prefix = "";
	}

	if ($name == 'stock') {
		$datum = isThereEnoughStock($datum);
	}

	$datum = $prefix . $datum;

	$line = "<p id='product_$name'>";
	if ($showHeading == true) {
		$line = $line . "<h3>$name</h3>";
	}
	$line = $line . "$datum</p>";

	return $line;
}

function prepareProductList($limit, $db_link) {

	$prodnums = retrieveLatestProdNums($limit, $db_link);
	$lastProduct = $prodnums[$limit -1];
	$list = "";

	$section['name'] = "stock";
	$section['showHeading'] = false;
	$sections[] = $section;

	$section['name'] = "price";
	$section['showHeading'] = false;
	$sections[] = $section;

	$numberOfSections = count($sections);
		

	for ($i = 0; $i < $limit; $i++) {
		$productNum = $prodnums[$i];
		$product = getProduct($productNum, $db_link);
		$name = $product['name'];

		$list = $list .
			"<div class='product'>
			<h2><a href='products/?prodnum=$productNum'>$name</a></h2>";

		// Add a line for each section of requested detail relating to this product 
		for ($c = 0; $c < $numberOfSections; $c++) {
			$list = $list . 
					thisProductLine($sections[$c], $product);
		}
		
		$list = 
			$list . "</div>";

		if ($productNum == $lastProduct) { break; }
	}

	return $list;
}

function preparePageStart($title, $header, $styles, $includeSearch){
	// Declare doctype
	// Open html tag
	// Open body tag
	// Add relevant title and header,
	// including styles
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
			<h1><a href='..'>$header</a></h1>
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

function prepareOptionList($values, $quantity) {
	$list = "";
	for ($i = 0; $i < $quantity; $i++) {
		$name = $values[$i];
		$list = $list . "<option value='$name'>$name</option>";
	}
	return $list;
}

function preparePageEnd() {
	// Close 'main_section', 'body' and 'html' tags
	// Open and close footer
	$bottom =
		"</div>
		<footer>
		</footer>
		</body>
		</html>";

	return $bottom;
}

function isThereEnoughStock($stock) {

	switch ($stock) {
			case 0:
				$stockMessage = "Out of Stock";
				break;
			default:
				$stockMessage = "$stock in stock";
	}

	return $stockMessage;
}

function getStyles($levelsDown) {

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