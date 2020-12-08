<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect the user to the Checkout Form Page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: checkout.htm");
} else {
    header("location: login.php");
  }
