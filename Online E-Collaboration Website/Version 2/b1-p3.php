<?php
// Initialize the session
session_start();
require 'db.php';
require 'jobs.php';
$result = mysqli_query($link, 'select * from jobs');

// Delete product in cart
if (isset ( $_GET ['index'] ) ) {
    $currentJob = trim($_GET['index']);
    // remove job from the session variable
	// $jobs = unserialize ( serialize ( $_SESSION ['jobs'] ) );
	// unset ( $jobs [$_GET ['index']] );
	// $jobs = array_values ( $jobs );
    // $_SESSION ['cart'] = $jobs;
    // $currentJob = unserialize ( serialize ( $_SESSION ['jobId'] ) );
    // remove the instance of the job from the database

    $query = "DELETE FROM jobs WHERE job_id = ?";

    if($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $par1);
        $par1 = $currentJob;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
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

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for DELETE should be here  </h4>";
}
?>       
<p> <br>
<p> <br>
<p> <br>
<H4> The page for DELETE should be here  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 90%;">
    <tbody>
        <tr style="border-bottom: 1px solid">
            <th>Title</th>
            <th>JobType</th>
            <th>JobOwner</th>
            <th>Date Created</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Delete</th>
        </tr>
    
	    <?php while($jobsList = mysqli_fetch_object($result)) { ?>
		<tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $jobsList->title; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->job_type; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->owner; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->date_created; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->deadline; ?></td>
			<td style="text-align: center;"><?php echo $jobsList->status; ?></td>
            <td>
                <a href="b1-p3.php?index=<?php echo $jobsList->job_id; ?>" onclick="return confirm('Are you sure?')">
                    Delete
                </a>
            </td>
		</tr>
	    <?php } ?>
    </tbody>
</table>

</body>
</html>