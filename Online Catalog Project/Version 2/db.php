<?php
   // Enter your Host, username, password, database below.
   // If you must work with a CS shared folder then YourUserName = MySQL account name for the given folder ...
 
	$con = mysqli_connect('localhost', 'b_f20_36', 'ydjysa', 'b_f20_36_db');

   if (mysqli_connect_errno()) {		
       echo "Failed to connect to MySQL: " . mysqli_connect_error();		
       die();	
   }
?>