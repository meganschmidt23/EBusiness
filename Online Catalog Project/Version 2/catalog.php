<?php
	require 'db.php';
	$result = mysqli_query($link, 'select * from product');
?>

<html>
<body>
<center>
<h3>CATALOG</h3>
<table cellpadding="2" cellspacing="2" border="0" >
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Price</th>
		<th>Buy</th>
	</tr>
	<?php while($product = mysqli_fetch_object($result)) { ?>
		<tr>
			<td><?php echo $product->id; ?></td>
			<td><?php echo $product->name; ?></td>
			<td><?php echo $product->price; ?></td>
			<td><a href="cart.php?id=<?php echo $product->id; ?>">ADD</a></td>
		</tr>
	<?php } ?>
</table>
<p>
<a href="cart.php">View CART</a> 

</body>
</html>

