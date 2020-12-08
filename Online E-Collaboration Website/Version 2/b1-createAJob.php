<?php

session_start();
require 'db.php';

$job_status = 0; //maybe?
$job_owner = 1; //Hard code ?
$job_title = $job_instructions = $job_deadline = $job_created = "";
$job_title_err = $job_ins_err = $deadline_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $query = "INSERT INTO job_T(job_status, job_owner, job_title, job_instructions, job_deadline, job_created) VALUES (?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $query)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iissss", $param_job_status, $param_job_owner, $param_job_title, $param_job_ins, $param_job_deadline, $param_job_created);
            
            // Set parameters
            $param_job_status = 0;
            $param_job_owner = 1;
            $param_job_title = trim($_POST["job_title"]);
            $param_job_ins = trim($_POST["job_instructions"]);
            $param_job_deadline = trim($_POST["job_deadline"]);
            $param_job_created = date("Y-m-d H:i:s");

            mysqli_stmt_execute($stmt);

        // Close statement
		mysqli_stmt_close($stmt);
		echo "Completed!";
        }
    }
    
    // Close connection
    mysqli_close($link);
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
<h4><B>Create a Job</B></h4>
    
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
   			
	<!-- Job title -->         
	<div <?php echo (!empty($job_title_err)) ? 'has-error' : ''; ?>">
	    <label>Job Title</label>
	    <input type="text" name="job_title" value="<?php echo $job_title; ?>">
	    <span><?php echo $job_title_err; ?></span>
	</div> 
	<p>
				
	<!-- Job Instructions -->
	<div  <?php echo (!empty($job_ins_err)) ? 'has-error' : ''; ?>">
	    <label>Job Instructions</label>
	    <input type="text" name="job_instructions" value="<?php echo $job_instructions; ?>">
	    <span><?php echo $job_ins_err; ?></span>
	</div>
	<p>

	<!-- Job Deadline -->
	<div  <?php echo (!empty($deadline_err)) ? 'has-error' : ''; ?>">
	    <label>Job Deadline</label>
	    <input type="date" name="job_deadline" value="<?php echo $job_deadline; ?>">
	    <span><?php echo $deadline_err; ?></span>
	</div>
	<p>   
	            
	<div>
	    <a href="b1_createAJob.php">RESET</a> 
	    <input type="submit" value="Enter"> 
	</div> 

</form>

</CENTER>

</body>
</html>