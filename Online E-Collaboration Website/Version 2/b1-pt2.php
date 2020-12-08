<?php
// Initialize the session
session_start();
require_once "db.php";

// Define variables and initialize with empty values
$title = $template_type = $owner = "";
$title_err = $template_type_err = $owner_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate title, job_type, owner, status
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    }
    else if (empty(trim($_POST["template_type"]))){
        $template_type_err = "Please enter a job type.";
    }
    else if (empty(trim($_POST["owner"]))){
        $owner_err = "Please enter an owner.";
    }
	else {
        // Prepare a insert into statement
        $sql = "INSERT INTO templates (title, template_type, owner, date_created) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_template_type, $param_owner, $param_date_created);
            
            // Set parameters
            $param_title = trim($_POST["title"]);
            $param_template_type = trim($_POST["template_type"]);
            $param_owner = trim($_POST["owner"]);
            $param_date_created = date("Y-m-d H:i:s");

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
            } 

            else {
                echo "Oops! Something went wrong. Please try again later.";
        	}

        // Close statement
        mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?> 

<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

<CENTER>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for CREATE should be here  </h4>";
}
?>

<h4><B>CREATE A TEMPLATE</B></h4>
    
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
   			
	<!-- title -->         
	<div <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
	    <label>Title</label>
	    <input type="text" name="title"  value="<?php echo $title; ?>">
	    <span><?php echo $title_err; ?></span>
	</div> 
	<p>

	<!-- template_type -->
	<div  <?php echo (!empty($template_type_err)) ? 'has-error' : ''; ?>">
	    <label>Template Type</label>
	    <input type="text" name="template_type"  value="<?php echo $template_type; ?>">
	    <span><?php echo $template_type_err; ?></span>
	</div>
	<p>
				
	<!-- owner -->
	<div  <?php echo (!empty($owner_err)) ? 'has-error' : ''; ?>">
	    <label>Owner</label>
	    <input type="text" name="owner"  value="<?php echo $owner; ?>">
	    <span><?php echo $owner_err; ?></span>
	</div>
	<p>  
	            
	<div>
	    <a href="b1-pt2.php">RESET</a> &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
	    <input type="submit" value="Enter"> 
	</div> 

</form>

</CENTER>

</body>
</html>