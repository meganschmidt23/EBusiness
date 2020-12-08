<?php

   // Enter your Host, username, password, database below.
   // If you must work with a CS shared folder then YourUserName = MySQL account name for the given folder ...

/*server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'b_f20_36');
define('DB_PASSWORD', 'ydjysa');
define('DB_NAME', 'b_f20_36_db');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>

