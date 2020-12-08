<?php
// Initialize the session
session_start();
?>


<html>
<head>
 <title> ABC Inc. </title> 
</head> 

<BODY>
        <H2> <CENTER> ABC Inc.</CENTER> </H2>
	<CENTER><IMG SRC="images/science.gif" width="100" height="80"> </CENTER>       
<?php 
if(!empty($_SESSION["username"])){
echo "<div align=\"right\"><h3>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font> </h3></div>";
}
?> 

        <HR> <CENTER><b>
		 <a href="register.php" target="BOT">Register</a> | 
		 <a href="login.php" target="BOT">Login</a>  |
		 <a href="catalog.php" target="BOT">SHOPPING</a>  |
		 <a href="cart.php" target="BOT">CART</a>  |
		 <a href="logout.php" target="BOT">Logout</a> </CENTER>
	<HR>
	<p>
   </BODY>
</html>

