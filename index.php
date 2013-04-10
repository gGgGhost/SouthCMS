<!DOCTYPE html>
<html>
<head>
	<title>
		SouthCMS E-commerce
	</title>
	<link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div id="phpstuff">
		<?php
		require_once 'login.php';
		require_once 'util.php';
		getConnected($db_login);
		?>
	</div>

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

</body>

</html>