<!DOCTYPE html>
<html>
<head>
	<title>
		SouthCMS E-commerce
	</title>
	<link href="../api/style.css" type="text/css" rel="stylesheet" />
	<link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>

<header>
	<h1>SouthCMS</h1>
</header>

<div id="main_section">

	<figure class="big_button option" id="add_product">
		<img src="images/icons/128x128/plus.png" class="icon-biggest" />
		<figcaption>
			<h1 class="big_button-caption">Add Product</h1>
			<p>Add a new product to the database</p>
		</figcaption>
	</figure>
	
	<figure class="big_button option">
		<img src="images/icons/128x128/chart.png" />
		<figcaption>
			<h1 class="big_button-caption">charts &amp; figures</h1>
			<p>Inspect sales data and create charts</p>
		</figcaption>
	</figure>

	<figure class="big_button option">
		<img src="images/icons/128x128/world.png" />
		<figcaption>
			<h1 class="big_button-caption">orders</h1>
			<p>View new, current, and old customer orders</p>
		</figcaption>
	</figure>

	<div id="phpstuff">
		<?php require_once 'setup.php';?>
	</div>

	
	<form name="add_product" id="product_form" class="empty">
		<p>
			<label for="name"><h3>Name/Short Title of this product</h3></label>
			<p>
				<input type="text" name="name" placeholder="40 character max" maxlength="40" />
			</p>
		</p>

		<p>
			<label for="description"><h3>Product Description</h3></label>
			<p>
				<textarea name="description" placeholder="140 character max" maxlength="140"></textarea>
			</p>
		</p>
		<p>
			<label for="cost"><h3>How much does this product cost you per unit?</h3></label>
			<p>
				<input type="text" name="cost" placeholder="&pound;" maxlength="6" />
		</p>
		<p>
			<label for="price"><h3>How much will you sell your product for per unit?</h3></label>
			<p>	
				<input type="text" name="price" placeholder="&pound;" maxlength="6" />
			</p>
		</p>
		<p>
			<label for="quantity"><h3>How many of this item do you initially have in stock?</h3></label>
			<p>
				<input type="text" name="quantity" placeholder="quantity in stock" maxlength="6" />
			</p>
		</p>
		<p>
			<label for="code"><h3>Is there an associated product code?</h3></label>
			<p>
				<input type="text" name="code" placeholder="20 character max"  maxlength="20" />
			</p>
		</p>
		<p>
			<label for="file_upload"><h3>Upload an image?</h3></label>
			<p>
				<input type="file" id="file_upload" name="file_upload" />
			</p>
		</p>

		<input type="submit" id="submit" value="Submit New Product" formaction='../api/products/' formmethod="POST" />
		<input type="reset" value="Reset Form" />
	</form>
	<script src="js/db_functions.js"></script>
</div>

<footer>

</footer>

</body>

</html>