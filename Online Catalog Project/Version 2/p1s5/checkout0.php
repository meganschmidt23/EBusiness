<?php
session_start ();
require 'db.php';
require 'item.php';

// Define variables and initialize with empty values
$username = $password = $message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Get username
    $username = trim($_POST["username"]);        

    // Get the password
    $password = trim($_POST["password"]);    
        
    // Prepare an insert statement
    $sql = "INSERT INTO p_users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = $password; 
            
            // Attempt to execute the prepared statement
            mysqli_stmt_execute($stmt);            

            // Close statement
            mysqli_stmt_close($stmt);
        }

// Save new order
mysqli_query($con, 'insert into orders(name, datecreation, status, username)
values("Example Order", "'.date('Y-m-d').'", 0, "testUser")');
$ordersid = mysqli_insert_id($con);

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
<h2><?php echo  $message ?> </h2>

</body>
</html>


