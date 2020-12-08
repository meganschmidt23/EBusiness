<?php
session_start ();
require 'db.php';
require 'item.php';

if(empty($_SESSION["cart"]) && !isset($_GET ['id']) ) {
  echo "<center>The shopping CART is Empty. <p>Please go to SHOPPING/CATALOG to add items to the CART!";
  exit(1);
}

if (isset ( $_GET ['id'] ) && !isset($_POST['update'])) {

	$result = mysqli_query ( $link, 'select * from a_products where pid=' . $_GET ['id'] );
	$product = mysqli_fetch_object ( $result );
	$item = new Item ();
	$item->id = $product->pid;
	$item->name = $product->name;
	$item->price = $product->price;
	$item->quantity = 1;
	// Check product is existing in cart
	$index = - 1;
	if (isset ( $_SESSION ['cart'] )) {
		$cart = unserialize ( serialize ( $_SESSION ['cart'] ) );
		for($i = 0; $i < count ( $cart ); $i ++)
		if ($cart [$i]->id == $_GET ['id']) {
			$index = $i;
			break;
		}
	}
	if ($index == - 1)
	$_SESSION ['cart'] [] = $item;
	else {
		$cart [$index]->quantity ++;
		$_SESSION ['cart'] = $cart;
	}
}

// Delete product in cart
if (isset ( $_GET ['index'] ) && !isset($_POST['update'])) {
	$cart = unserialize ( serialize ( $_SESSION ['cart'] ) );
	unset ( $cart [$_GET ['index']] );
	$cart = array_values ( $cart );
	$_SESSION ['cart'] = $cart;
}

// Update quantity in cart
if(isset($_POST['update'])) {
	$arrQuantity = $_POST['quantity'];

	// Check validate quantity
	$valid = 1;
	for($i=0; $i<count($arrQuantity); $i++)
	if(!is_numeric($arrQuantity[$i]) || $arrQuantity[$i] < 1){
		$valid = 0;
		break;
	}
	if($valid==1){
		$cart = unserialize ( serialize ( $_SESSION ['cart'] ) );
		for($i = 0; $i < count ( $cart ); $i ++) {
			$cart[$i]->quantity = $arrQuantity[$i];
		}
		$_SESSION ['cart'] = $cart;
	}
	else
		$error = 'Quantity is InValid';
}

?>

<html>
<body>

<center>
<h3> CART </h3>
<?php echo isset($error) ? $error : ''; ?>
<form method="post">
	<table cellpadding="2" cellspacing="2" border="1">
		<tr>
			<th>Option</th>
			<th>Id</th>
			<th>Name</th>
			<th>Price</th>
			<th>Quantity 
			</th>
			<th>Sub Total</th>
		</tr>
		<?php
		$cart = unserialize ( serialize ( $_SESSION ['cart'] ) );
		$s = 0;
		$index = 0;
		for($i = 0; $i < count ( $cart ); $i ++) {
			$s += $cart [$i]->price * $cart [$i]->quantity;
			?>
		<tr>
			<td><a href="cart.php?index=<?php echo $index; ?>"
				onclick="return confirm('Are you sure?')">Delete</a></td>
			<td><?php echo $cart[$i]->id; ?></td>
			<td><?php echo $cart[$i]->name; ?></td>
			<td><?php echo $cart[$i]->price; ?></td>
			<td><input type="text" value="<?php echo $cart[$i]->quantity; ?>"
				style="width: 50px;" name="quantity[]"></td>
			<td><?php echo $cart[$i]->price * $cart[$i]->quantity; ?></td>
		</tr>
		<?php
		$index ++;
		}
		?>
		<tr>
			<td colspan="5" align="right">Sum</td>
			<td align="left"><?php echo $s; ?></td>
		</tr>
	</table>

<?php $_SESSION['total'] = $s; ?>

<p><center>
<input type="image" src="images/Update-Button.gif" width="50" height="50"> <input type="hidden" name="update">
</p>
</form>
<a href="catalog.php">SHOP MORE</a> &emsp;&emsp;|&emsp;&emsp; <a href="precheckout.php">CHECKOUT</a>
</body>
</html>