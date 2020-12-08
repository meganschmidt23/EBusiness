<?php
session_start();
require 'db.php';
require 'jobs.php';
if( isset($_GET['job_id']) ) {
    $task_result = mysqli_query($link, "SELECT * FROM tasks WHERE job_id = " . $_GET['job_id']);
    $job_result = mysqli_query($link, "SELECT * FROM jobs WHERE job_id = " . $_GET['job_id']);
    $job = mysqli_fetch_object($job_result);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo 'inside if statement';
    // Get deadline
    $deadline = trim($_POST["new-deadline"]);        

    // Get job status
    $status = trim($_POST["job-status"]); 

    // Get job id to edit
    $job_id_to_edit = trim($_POST["job-id"]); 

    $sql = "UPDATE tasks SET deadline = ?, status = ? WHERE job_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $par1, $par2, $par3);
        
        // Set parameters
        $par1 = $deadline;
        $par2 = $status; 
        $par3 = $job_id_to_edit;
        echo $par3;

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);        

        // Close statement
        mysqli_stmt_close($stmt);
        header("location: b1-p4.php");
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <section class="section box">
        <h1 class="title"><?php echo $job->title; ?></h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <?php while($taskList = mysqli_fetch_object($task_result)) { ?>

                <h2 class="title is-4">Task Type: <?php echo $taskList->task_type; ?></h2>

                <input type="hidden" name="job-id" value="<?php echo $_GET['job_id'];?>">

                <div class="field">
                    <label class="label" for="new-deadline">Change Deadline</label>
                    <div class="control">
                        <input class="input" name="new-deadline" type="datetime-local">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <label for="job-status" class="label">Task Status</label>
                        <label class="radio">
                            <input type="radio" name="job-status" value="Complete">
                            Complete
                        </label>
                        <label class="radio">
                            <input type="radio" name="job-status" value="Open">
                            Open
                        </label>
                    </div>
                </div>

                <div class="control">
                    <input class="button" type="submit" value="Submit">
                </div>

            <? } ?>

        </form>
    </section>
</body>
</html>