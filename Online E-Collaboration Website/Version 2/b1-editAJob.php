<?php
// Initialize the session
session_start();
require 'db.php';

$result = mysqli_query($link, 'select * from job_T');

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Get deadline
    $job_title = trim($_POST["job_title"]);
    $job_instructions= trim($_POST["job_instructions"]);
    $deadline = trim($_POST["deadline"]);        

    $sql = "UPDATE job_T SET job_title = ?, job_description = ?, deadline = ? WHERE job_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $par1, $par2, $par3);
        
        // Set parameters
        $par1 = $job_title;
        $par2 = $job_instructions; 
        $par3 = $deadline;

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);        

        // Close statement
        mysqli_stmt_close($stmt);
    }
}



?> 

<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        th, td{ border-left:1px solid #ccc; border-right: 1px solid #ccc;}
    </style>
</head>
<body>

<CENTER>
      
<p> <br>
<p> <br>
<p> <br>
<H4> Edit a Job  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 90%">
    <tbody>
        <tr style="border-bottom: 1px solid">
            <th style="text-align: center;">Job ID</th>
            <th style="text-align: center;">Job Title</th>
            <th style="text-align: center;">Job Instructions</th>
            <th style="text-align: center;">Deadline</th>
            <th style="text-align: center;">Date Created</th>
            <th style="text-align: center;">Edit</th>
        </tr>
    
	    <?php while($jobs= mysqli_fetch_object($result)) { ?>
        <tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $jobs->job_id; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_title; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_instructions; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_deadline; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_created; ?></td>
            <td><a href="./b1-edit-a-job.php?job_id=<?php echo $jobs->job_id; ?>" id="edit-btn">Edit</button></a>
        </tr>
        <?php } ?>  
    </tbody>
</table>

</CENTER>

</body>
</html>