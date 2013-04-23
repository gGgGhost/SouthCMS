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

function prepareProductDetails ($sections, 
								$product) {
	$lines = "";
	for ($i = 0; $i < count($sections); $i++) {
		$lines = $lines . getProductSection($sections[$i], $product);
	}
	return $lines;
}

function getProductSection ($sectionDetails, 
							$product, $tag = "id") {

	$sectionName = $sectionDetails['name'];
	$datum = $product[$sectionName];
	$thisSegment = $datum;
	$showHeading = $sectionDetails['showHeading'];

	if ($sectionName == 'catname') {
		$thisSegment = wrapWithLink("../categories/?name=$datum", $thisSegment);
	}

	if (isset($sectionDetails['prefix'])) {
		$prefix = $sectionDetails['prefix'];
		$thisSegment = $prefix . $thisSegment;
	}
	
	if ($sectionName == 'stock') {
		$thisSegment = isThereEnoughStock($datum);
	}

	$section = "<p $tag='product_$sectionName'>";

	if ($showHeading == true) {
		if ($sectionName =='catname') {$sectionName='category';}
		$section = $section . "<h3>$sectionName</h3>";
	}

	$section = $section . "$thisSegment</p>";

	return $section;
}

function wrapWithLink ($href, $string) {
	$wrappedString = "<a href='$href'>"
					. $string
					. "</a>";
	return $wrappedString;
}

function prepareProductList ($limit, 
							 $db_link,
							 $category = 'all', $levelsDown = 0) {

	$ids = retrieveProductIds($limit, $db_link, $category);
	$list = "";
	$tag ="class";
	if ($category == 'all') {
		$section['name'] = "catname";
		$section['showHeading'] = false;
		$section['prefix'] = " . ";
		$sections[] = $section;
	}

	$section['name'] = "stock";
	$section['showHeading'] = false;
	$sections[] = $section;

	$section['name'] = "price";
	$section['showHeading'] = false;
	$section['prefix'] = "&pound;";
	$sections[] = $section;

	$numberOfSections = count($sections);
	
	switch ($levelsDown) {
		case 0:
			$dir = "";
		default:
			$dir = "../";
	}

	for ($i = 0; $i < $limit; $i++) {
		$id = $ids[$i];
		$product = getProduct($id, $db_link);
		$name = $product['name'];


		$list = $list .
			"<div class='product'>
			<h2 $tag='product_name'><a href='" . $dir . 
			"products/?id=$id'>$name</a></h2>";

		// Add a line for each section of requested detail relating to this product 
		for ($c = 0; $c < $numberOfSections; $c++) {
			$list = $list . 
					getProductSection($sections[$c], $product, $tag);
		}
		
		$list = 
			$list . "</div>";
	}

	return $list;
}

function preparePageStart ($title, 
						   $header, 
						   $styles, 
						   $includeSearch, 
						   $levelsDown = 1) {

	// Make sure main link points home,
	// depending on place in structure
	switch ($levelsDown) {
		case 0:
			$homeLink = "";
			break;
		default:
			$homeLink = "..";
			break;
	}
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

function preparePageEnd () {
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

function isThereEnoughStock ($stock) {

	switch ($stock) {
			case 0:
				$stockMessage = "Out of Stock";
				break;
			default:
				$stockMessage = "$stock in stock";
	}

	return $stockMessage;
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