<?php session_start();
require 'db.php';

$result = mysqli_query($link, 'select * from job_T');

 if (isset ( $_GET ['job_id'] ) ) {
    $job_id = trim($_POST["job_id"]);
    $currentJob = trim($_GET['job_id']);
    $sql = "SELECT task_id FROM jobDetails_T WHERE job_id = $job_id";
    $result = mysqli_stmt_execute($sql);
    mysqli_stmt_close($sql)
    $task_id = $result['task_id'];
    $sql2 = "SELECT task_status FROM task_T WHERE task_id = $task_id";
    $result2 = mysqli_stmt_execute($sql2);
    mysqli_stmt_close($sql2)
    $check = 0;
    while ($result2){
        if($result = 1 ){
            $check = 1;
        }
    } 
    if ($check = 0){
        $sql3 = "UPDATE job_T SET job_status = 2 WHERE job_id = $job_id";
        mysqli_stmt_execute($sql3);
        mysqli_stmt_close($sql3);
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
<H4> Finish a Job  </H4>
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
                <a href="b1-finishAJob.php?job_id=<?php echo $jobs->job_id; ?>" onclick="return confirm('Are you sure you want to finish this job?')">
                    Finish Job
                </a>
            </td>
        <?php } ?>  
    </tbody>
</table>

</CENTER>

</body>
</html>