<?php session_start();
require 'db.php';

$result = mysqli_query($link, 'select * from job_T');

if(isset ( $_GET ["job_id"] ) ){
 
    $job_id = trim($_GET["job_id"]);
          
    $sql = "UPDATE job_T SET job_status = ? WHERE job_id = ?";
            
   if($stmt = mysqli_prepare($link, $sql)){
       // Bind variables to the prepared statement as parameters
       mysqli_stmt_bind_param($stmt, "ii", $status1, $par1);
       
           // Set parameters
           $status1 = 1;
           $par1 = $job_id;
   
   
           // Attempt to execute the prepared statement
           mysqli_stmt_execute($stmt);        
   
           // Close statement
           mysqli_stmt_close($stmt);
           echo "Completed!";
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
<H4> Resume a Job  </H4>
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
            <td style="text-align: center;"><?php echo $jobs->job_status; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_title; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_instructions; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_deadline; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_created; ?></td>
            <td>
                <a href="b1-resumeAJob.php?job_id=<?php echo $jobs->job_id; ?>" onclick="return confirm('Are you sure you want to resume this job?')">
                    Resume Job
                </a>
            </td>
        <?php } ?>  
    </tbody>
</table>

</CENTER>

</body>
</html>