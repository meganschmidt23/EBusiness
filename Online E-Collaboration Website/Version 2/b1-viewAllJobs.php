<?php session_start();
require 'db.php';
$result = mysqli_query($link, 'select * from job_T');
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

<CENTER>
<h3>View All Jobs</h3>



<table border="0" cellpadding="5" style="border-collapse: collapse; width: 1000px;">
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
		</tr>
	<?php } ?>
</tbody>
</table>      

</CENTER>

</body>
</html>