<?php
// Initialize the session
session_start();
	require 'db.php';
	// hard-code a user from Unit A. We said user_id is a varchar
	$_SESSION['user_id'] = '1';
	if( isset ($_SESSION['user_id']) ) {
		// get all the jobs where the user is an owner. Later, this will be all jobs/tasks associated with the user
		$result = mysqli_query($link, 'select * from jobs WHERE owner = ' . $_SESSION['user_id']);
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
<h3>JOBS</h3>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for VIEW should be here  </h4>";
}
?>

<table border="0" cellpadding="5" style="border-collapse: collapse; width: 1000px;">
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

	<?php while($job = mysqli_fetch_object($result)) 
	{ ?>
		<tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $job->job_id; ?></td>
			<td style="text-align: center;"><?php echo $job->title; ?></td>
			<td style="text-align: center;"><?php echo $job->job_type; ?></td>
			<td style="text-align: center;"><?php echo $job->owner; ?></td>
			<td style="text-align: center;"><?php echo $job->date_created; ?></td>
			<td style="text-align: center;"><?php echo $job->deadline; ?></td>
			<td style="text-align: center;"><?php echo $job->status; ?></td>
		</tr>
	<?php } ?>
</tbody>
</table>      

</CENTER>

</body>
</html>