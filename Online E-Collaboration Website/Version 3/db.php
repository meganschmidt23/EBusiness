<?php

   // Enter your Host, username, password, database below.
   // If you must work with a CS shared folder then YourUserName = MySQL account name for the given folder ...

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'b_f20_27');
define('DB_PASSWORD', 'afdauc');
define('DB_NAME', 'b_f20_27_db');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>
