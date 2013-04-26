<?php
// Link into the api
require_once "../api/categories/index.php";
require_once "../api/products/index.php";
require_once "../api/page.php";
$db_link = getConnected();

$optionList = "";
$numberOfCategories = countCategories($db_link);
if ($numberOfCategories > 0) {
	$categoryNames = getListOfCategoryNames($db_link);
	$optionList = prepareCategoryOptionList($categoryNames, $numberOfCategories); 
}

// Set parameters
$levelsDown = 0;
$pageTitle= $pageHeader = "SouthCMS";
$styles = getStyles($levelsDown);
$includeSearch = false;

$productForm =
	"<form name='add_product' id='product_form' class='empty'>
		<p>
			<label for='name'><h3>Name/Short Title of this product</h3></label>
			<p>
				<input type='text' name='name' placeholder='40 character max' maxlength='40' />
			</p>
		</p>

		<p>
			<label for='description'><h3>Product Description</h3></label>
			<p>
				<textarea name='description' placeholder='140 character max' maxlength='140'></textarea>
			</p>
		</p>
		<p>
			<label for='category'><h3>Product Category</h3></label>
			<p>
				<select name='category' id='category'>
					<option value='NEW'>NEW</option>
					$optionList
				</select>
				<input type='text' id='new_category'
				name='new_category' placeholder='new category name' maxlength='15' />
			</p>
		</p>
		<p>
			<label for='cost'><h3>How much does this product cost you per unit?</h3></label>
			<p>
				<input type='text' name='cost' placeholder='&pound;' maxlength='6' />
		</p>
		<p>
			<label for='price'><h3>How much will you sell your product for per unit?</h3></label>
			<p>	
				<input type='text' name='price' placeholder='&pound;' maxlength='6' />
			</p>
		</p>
		<p>
			<label for='quantity'><h3>How many of this item do you initially have in stock?</h3></label>
			<p>
				<input type='text' name='quantity' placeholder='quantity in stock' maxlength='6' />
			</p>
		</p>
		<p>
			<label for='code'><h3>Is there an associated product code?</h3></label>
			<p>
				<input type='text' name='code' placeholder='20 character max'  maxlength='20' />
			</p>
		</p>
		<p>
			<label for='file_upload'><h3>Upload an image?</h3></label>
			<p>
				<input type='file' id='file_upload' name='file_upload' />
			</p>
		</p>

		<input type='submit' id='submit' value='Submit New Product' formaction='../api/products/' formmethod='POST' />
		<input type='reset' value='Reset Form' />
	</form>";

$bigButton['id'] = 'add_product';
$bigButton['img'] = 'plus.png';
$bigButton['captionHead'] = 'Add Product';
$bigButton['caption'] = "Add a new product to the database";
$bigButtons[] = $bigButton;
$bigButton['id'] = 'view_products';
$bigButton['img'] = 'tag.png';
$bigButton['captionHead'] = 'View Products';
$bigButton['caption'] = "View a list of all products in the database";
$bigButtons[] = $bigButton;
$bigButton['id'] = 'view_charts';
$bigButton['img'] = 'chart.png';
$bigButton['captionHead'] = 'charts &amp; figures';
$bigButton['caption'] = "Inspect sales data and create charts";
$bigButtons[] = $bigButton;
$bigButton['id'] = 'view_orders';
$bigButton['img'] = 'world.png';
$bigButton['captionHead'] = 'orders';
$bigButton['caption'] = "View new, current, and old customer orders";
$bigButtons[] = $bigButton;
$bigButtonString = "";
for ($i = 0; $i < count($bigButtons); $i++) {
	$bigButtonString = $bigButtonString . buildBigButton($bigButtons[$i]);
}

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, 
								  $levelsDown)
								. $bigButtonString;

$page['content'] = "<div id='response_area'>
					</div>"
				 . $productForm;

$page['end'] = "<script src='../api/js/ajax.js'></script>"
			 . "<script src='js/cms.js'></script>"
			 . "<script src='../api/js/validate.js'></script>"
			 . preparePageEnd('cms', '');

// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>
