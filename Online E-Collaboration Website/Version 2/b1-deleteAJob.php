<?php
// Initialize the session
session_start();
require 'db.php';
$result = mysqli_query($link, 'select * from job_T');

if (isset ( $_GET ['index'] ) ) {
    $currentJob = trim($_GET['index']);

    $query = "DELETE FROM job_T WHERE job_id = ?";
    $queryforDetails = "DELETE FROM jobDetails_T where job_id=?";

    if($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $par1);
        $par1 = $currentJob;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    if($stmt2 = mysqli_prepare($link, $queryforDetails)) {
        mysqli_stmt_bind_param($stmt, "i", $par1);
        $par1 = $currentJob;
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    }
}
?> 
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        th, td{ border-left:1px solid #ccc; border-right: 1px solid #ccc;}
    </style>
</head>
<body>
       
<p> <br>
<p> <br>
<p> <br>
<H4> Delete a Job  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 90%;">
    <tbody>
        <tr style="border-bottom: 1px solid">
        <th style="text-align: center;">Job ID</th>
		<th style="text-align: center;">Job Status</th>
		<th style="text-align: center;">Job Owner</th>
		<th style="text-align: center;">Job Title</th>
		<th style="text-align: center;">Instructions</th>
		<th style="text-align: center;">Deadline</th>
        </tr>
    
	    <?php while($jobs = mysqli_fetch_object($result)) { ?>
		<tr style="border-bottom: 1px solid">
        <td style="text-align: center;"><?php echo $jobs->job_id; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_status; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_owner; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_title; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_instructions; ?></td>
			<td style="text-align: center;"><?php echo $jobs->job_deadline; ?></td>
            <td>
                <a href="b1-deleteAJob.php?index=<?php echo $jobs->job_id; ?>" onclick="return confirm('Are you sure?')">
                    Delete
                </a>
            </td>
		</tr>
	    <?php } ?>
    </tbody>
</table>

</body>
</html>
