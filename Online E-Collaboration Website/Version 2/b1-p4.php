<?php
// Initialize the session
session_start();
require 'db.php';
require 'jobs.php';

$result = mysqli_query($link, 'select * from jobs');
$resultTasks = mysqli_query($link, 'select * from tasks');

$task_sql = "SELECT * FROM tasks WHERE job_id = ?";
if($stmt = mysqli_prepare($link, $task_sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $par1);
    
    // Set parameters
    $par1 = 1;
    // Execute statement
    mysqli_stmt_execute($stmt);  
    $task_result = mysqli_stmt_get_result($stmt);    

    // Close statement
    mysqli_stmt_close($stmt);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Get deadline
    $deadline = trim($_POST["new-deadline"]);        

    // Get job status
    $status = trim($_POST["job-status"]); 

    $sql = "UPDATE tasks SET deadline = ?, status = ? WHERE job_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $par1, $par2, $par3);
        
        // Set parameters
        $par1 = $deadline;
        $par2 = $status; 
        $par3 = 1;

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
    </style>
</head>
<body>

<CENTER>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for EDIT should be here  </h4>";
}
?>       
<p> <br>
<p> <br>
<p> <br>
<H4> The page for EDIT should be here  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 90%">
    <tbody>
        <tr style="border-bottom: 1px solid">
            <th style="text-align: center;">JobID</th>
            <th style="text-align: center;">Title</th>
            <th style="text-align: center;">Job Type</th>
            <th style="text-align: center;">Owner</th>
            <th style="text-align: center;">Date Created</th>
            <th style="text-align: center;">Deadline</th>
            <th style="text-align: center;">Status</th>
        </tr>
    
	    <?php while($jobsList = mysqli_fetch_object($result)) { ?>
        <tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $jobsList->title; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->job_type; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->owner; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->date_created; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->deadline; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->status; ?></td>
            <td><a href="./edit-form.php?job_id=<?php echo $jobsList->job_id; ?>" id="edit-btn">Edit</button></a>
        </tr>
        <!-- Empty row to separate job from tasks -->
        <tr>
        <th>TASKS</th>
        </tr>

        <?php while($taskList = mysqli_fetch_object($resultTasks)) { ?>
        <tr style="border-bottom: 1px solid">
			<td style="text-align: center;">Task ID: <?php echo $taskList->task_id; ?></td>
			<td style="text-align: center;">Job ID: <?php echo $taskList->job_id; ?></td>
			<td style="text-align: center;">Type: <?php echo $taskList->task_type; ?></td>
			<td style="text-align: center;">Communication Channel: <?php echo $taskList->communication_channel; ?></td>
			<td style="text-align: center;">Order: <?php echo $taskList->order; ?></td>
            <td style="text-align: center;">Deadline: <?php echo $taskList->deadline; ?></td>
			<td style="text-align: center;">Status: <?php echo $taskList->status; ?></td>
        </tr>      
	    <?php } ?>
        <?php } ?>  
    </tbody>
</table>

</CENTER>

</body>
</html>