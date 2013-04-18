<!DOCTYPE html>
<html>
<head>
	<title>
		SouthCMS E-commerce
	</title>
	<link href="style.css" type="text/css" rel="stylesheet" />

</head>
<body>

<header>
	<h1>SouthCMS</h1>
</header>

<div class="main_section">

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
			<label for="name">Name/Short Title of this product</label>
			<p>
				<input type="text" name="name" placeholder="name">
				</input>
			</p>
		</p>

		<p>
			<label for="description">Product Description</label>
			<p>
				<textarea name="description" placeholder="description"></textarea>
			</p>
		</p>
		<p>
			<label for="cost">How much does this product cost you per unit?</label>
			<p>
				<input type="text" name="cost" placeholder="cost">
				</input>
			</p>
		</p>
		<p>
			<label for="price">How much will you sell your product for per unit?</label>
			<p>	
				<input type="text" name="price" placeholder="price">
				</input>
			</p>
		</p>
		<p>
			<label for="quantity">How many of this item do you initially have in stock?</label>
			<p>
				<input type="text" name="quantity" placeholder="quantity">
				</input>
			</p>
		</p>
		<p>
			<label for="code">What code uniquely identifies this this product?</label>
			<p>
				<input type="text" name="code" placeholder="code">
				</input>
			</p>
		</p>

		<input type="submit" id="submit" value="Submit New Product" formaction='../api/products/' formmethod="POST" />
		<input type="reset" value="Reset Form" />
	</form>
	<script src="js/db_functions.js"></script>
</div>

</body>

</html>