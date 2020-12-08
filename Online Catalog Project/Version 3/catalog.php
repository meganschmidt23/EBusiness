<?php
	require 'db.php';
	$result = mysqli_query($link, 'select * from a_products');
?>

<html>
<body>
<center>
<h3>CATALOG</h3>

<table border="0" cellpadding="5" style="border-collapse: collapse; ">
  <tbody>
    <tr style="border-bottom: 1px solid">
 		<th>Image</th>
		<th>Name</th>
		<th>Price</th>
		<th>Buy</th>
	</tr>
	<?php while($product = mysqli_fetch_object($result)) { ?>
		<tr style="border-bottom: 1px solid">
			<td><IMG SRC="<?php echo $product->image; ?> " width="80" height="50"></td>
			<td><?php echo $product->name; ?></td>
			<td><?php echo $product->price; ?></td>
			<td><a href="cart.php?id=<?php echo $product->pid; ?>">ADD</a></td>
		</tr>
	<?php } ?>

</tbody>
</table>
<p>
<a href="cart.php">View CART</a> 

</body>
</html>

