<?php
session_start ();
require 'db.php';
require 'item.php';

// Define variables and initialize with empty values
$ship = $bank = $total = $message = $username = "";
$par1 = $par3 = $par5 = $par2 = $par4 = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Get ship
    $ship = trim($_POST["ship"]);        

    // Get the bank
    $bank = trim($_POST["bank"]);    

    // Get the TOTAL
    $total = $_SESSION ['total']; 

    // Prepare an insert statement
    $sql = "INSERT INTO p_orders(username, bank, total, ship, datecreation) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $par1, $par2, $par3, $par4, $par5);
            
            // Set parameters
            $par1 = "TestUser";
            $par2 = $bank; 
            $par3 = $total;
            $par4 = $ship; 
            $par5 = date('Y-m-d');
            
            // Save NEW order
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);      

            $ordersid = mysqli_insert_id($con);      

            // Close statement
            mysqli_stmt_close($stmt);
        }

// Save order details for new order
$cart = unserialize ( serialize ( $_SESSION ['cart'] ) );
for($i=0; $i<count($cart); $i++) {
	mysqli_query($con, 'insert into ordersdetail(productid, ordersid, price, quantity)
values('.$cart[$i]->id.', '.$ordersid.','.$cart[$i]->price.', '.$cart[$i]->quantity.')');
}

// Clear all products in cart
unset($_SESSION['cart']);


   $message = "Thanks for buying products. Click <a href=\"index.php\">here</a> to continue buy product.";
    
    // Close connection
    mysqli_close($con);
}
?>


<html>
<head>
    <title>Checkout</title>
</head>
<body>

<p></p>
<h2><?php echo  $message;  ?> </h2>

</body>
</html>


