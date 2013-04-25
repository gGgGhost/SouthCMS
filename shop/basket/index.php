<?php
// Link into the api
require_once "../../api/products/index.php";
require_once "../../api/page.php";

// Hook up to database
$db_link = getConnected();

$orderForm = "<form id='order_form' class='empty'>
		<p>
			<label for='name'><h3>Full Name</h3></label>
			<p>
				<input type='text' name='name' placeholder='25 character max' maxlength='25' />
			</p>
		</p>

		<p>
			<label for='address'><h3>Address</h3></label>
			<p>
				<input type='text' name='address' placeholder='40 character max' maxlength='40' />
			</p>
		</p>
		<p>
			<label for='postcode'><h3>Postcode</h3></label>
			<p>
				<input type='text' name='postcode' maxlength='8' />
			</p>
		</p>
		<input type='submit' id='confirm_order' value='Confirm Order' formaction='../api/products/' formmethod='POST' />
		</form>";

$levelsDown = 1;
$styles = getStyles($levelsDown);
$pageHeader = "very simple shop";
$pageTitle = "Basket - " . $pageHeader;
$includeSearch = true;

$basket = "<div id='basket'></div>";

$page['start'] = preparePageStart($pageTitle, $pageHeader, 
								  $styles, $includeSearch, $levelsDown, $basket);

$page['content'] = "<div id='basket_zone'><h2>Products in basket</h3>";

$page['end'] = "</div>" 
			 . $orderForm
			 . "<script src='../../api/js/ajax.js'></script>"
			 . "<script src='../js/basket.js'></script>"
 			 . "<script src='../js/order.js'></script>"
			 . preparePageEnd('shop');
		
// Output each page section in turn
echo($page['start']);
echo($page['content']);
echo($page['end']);

// End connection
mysqli_close($db_link); ?>