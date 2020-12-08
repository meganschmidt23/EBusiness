<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin
$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 001 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

// get job_id from $_GET from view-all-jobs.php
if( !empty($_GET["job_id"]) ) {
    $current_job_id = trim( $_GET["job_id"] );
}


if( isset($_GET['job_status']) && isset($_GET['job_id'])) {
    $current_job_id = trim($_GET["job_id"]);
    $job_status = trim($_GET["job_status"]);

    $job_sql = "UPDATE job_T SET job_status = ? WHERE job_id = ? AND job_owner = ?";

    if($stmt = mysqli_prepare($link, $job_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $job_status;
        $par2 = $current_job_id; 
        $par3 = $_SESSION['user_id'];

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }

    // update the tasks status based on the task details

    // if there is a task detail that was rejected (value of 2) than the task should have status rejected (value of 3)
    // if SUM = COUNT than task is approved 
    // if SUM != Count than task is still in-progress
    $check_job_status = mysqli_query($link, 'SELECT job_T.job_id, jobDetails_T.task_id, jobDetails_T.job_taskOrder, task_T.task_status FROM job_T INNER JOIN jobDetails_T ON job_T.job_id = jobDetails_T.job_id INNER JOIN task_T ON jobDetails_T.task_id = task_T.task_id WHERE task_T.task_status = 2 AND job_T.job_id = '. $current_job_id);
    if ($check_job_status->num_rows < 1) {
        $status_id = 1;
    }
    else {
        $status_id = 2;
    }

    $status_sql = "UPDATE job_T SET job_status = ? WHERE job_id = ?";

    if($stmt = mysqli_prepare($link, $status_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $status_id;
        $par2 = $current_job_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view the one job
$job_results = mysqli_query($link, 'SELECT * FROM job_T WHERE job_id = ' . $current_job_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View Job</title>
</head>
<body>
    <section class="hero" style="background-color: rgb(128,0,128);">
        <div class="hero-body">
            <div class="container has-text-centered has-text-white">
            <h1 class="title has-text-white">
                Job Management
            </h1>
            <h2 class="subtitle has-text-white">
                Create a Job
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="columns">
            <div class="column is-2">
                <div class="container">
                    <?php $current_topic = "job"; include "./vertical-menu.php"; ?>
                </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-8">
            <?php while($job = mysqli_fetch_object($job_results) )  { ?>
                <div class="box">
                    <h1 class="title is-6">
                        Job Title: <span class="is-uppercase"><?php echo $job->job_title; ?><span>
                        <?php if ($job->job_status == 1): ?>
                            <span> (Done)</span>
                        <?php elseif ($job->job_status == 2): ?>
                            <span> (In-Progress)</span>
                        <?php elseif ($job->job_status == 4): ?>
                                <span> Paused</span>     
                        <?php elseif ($job->job_status == 3): ?>
                            <span> (Deleted)</span>
                        <?php endif; ?>
                    </h1>
                    <hr>
                    <h2 class="title is-6">Job Instructions:</h2>
                    <p class="content">
                        <?php echo $job->job_instructions; ?>
                    </p>
                    <hr>
                    <h2 class="title is-6">Job Actions:</h2>
                    <form action="./view-one-job.php" method="GET">
                        <!-- Radio Buttons -->
                        <p>Current Job Status:
                            <?php if ($job->job_status == 1): ?>
                                <span> Done</span>
                            <?php elseif ($job->job_status == 2): ?>
                                <span> In-Progress</span>
                            <?php elseif ($job->job_status == 3): ?>
                                <span> Deleted</span>
                            <!-- <?php elseif ($job->job_status == 4): ?>
                                <span> Paused</span>     -->
                            <?php endif; ?>
                            </p>
                    </h1>
                        <br>
                        <p>Change Job Status: </p>
                        <div class="field">
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="job_status" value="1">
                                    Done
                                </label>
                                <label class="radio">
                                    <input type="radio" name="job_status" value="2">
                                    In Progress
                                </label>
                                <!-- <label class="radio">
                                    <input type="radio" name="job_status" value="4">
                                    Pause
                                </label>

                                <label class="radio">
                                    <input type="radio" name="job_status" value="3">
                                    Deleted
                                </label> -->

                            </div>
                        </div>

                        <input type="hidden" name="job_id" value="<?php echo $current_job_id; ?>">

                        <br>
                        <!-- Submit Button -->
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-info">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h2 class="title is-6">Job Admin:</h2>
                    <a class="<?php if(admin_form_disabled == "disabled"){echo "is-hidden";} ?>" href="./job-admin.php?job_id=<?php echo $current_job_id; ?>"><button class="button is-success" <?php echo $admin_form_disabled;?>>View & Change</button></a>
                </div>
            <?php } ?>
            </div>
            <div class="column is-1"></div>
        </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                A SQL statement fetches data from job_T based on the job_id passed as a query parameter.
                A SQL statement fetches the appropriate jobDetails_T row based on job_id and user_id.
                Most of the data on this page comes from those two queries.
                For job data, this is currently a placeholder; we would pass an id to get the right form or file.
                For job actions, this is a form to toggle job_status between 1, 2, or 3 - done, in-progress, or deleted.
                After submitting this form, a SQL query updates the job_status attribute in the job_T if need be.
                There are several SQL queries to convert id numbers into legible in-progress/completed text.
                There is a hidden input in the task actions form to preserve the job_id that was passed as a query parameter.
                The last feature of the page is the task admin link.
                At the moment, this button is visible to all users.
                In the future, Unit A will provide the role_id alongside the user_id; we can then hide/display the task admin based on that role_id.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>
