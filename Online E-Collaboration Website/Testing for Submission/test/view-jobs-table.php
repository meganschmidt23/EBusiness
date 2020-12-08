<?php
// Initialize the session
session_start();
require 'db.php';


$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}


if(isset($_GET['change_status']) && isset($_GET['job_id'])) {
    $update_job = $_GET['job_id'];
    $update_status_id = $_GET['change_status'];

    $update_task_sql = 'UPDATE job_T SET job_status = ? WHERE job_id = ?';

    if($stmt = mysqli_prepare($link, $update_task_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $update_status_id;
        $par2 = $update_job;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view all tasks as an admin
if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $job_results = mysqli_query($link, 
    'SELECT * FROM job_T');
} else {
    // view all tasks where the SESSION user is listed on a taskDetails row
    $job_results = mysqli_query($link, 
    'SELECT * FROM job_T WHERE job_owner = '. $_SESSION['user_id']);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Job Table</title>
</head>
<body>
    <section class="hero" style="background-color: rgb(128,0,128);">
        <div class="hero-body">
            <div class="container has-text-centered has-text-white">
            <h1 class="title has-text-white">
                Job Management
            </h1>
            <h2 class="subtitle has-text-white">
                View A Job
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
            <div class="column is-10">
                <div class="box table-container">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th>Job Status</th>
                            <th>Job Title</th>
                            <th>Job Instructions</th>
                            <th>Job Created</th>
                            <th>Job Deadline</th>
                            <th>Edit Job</th>
                            <th>Admin Panel</th>
                            <th>Delete Job</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- While Loop to Generate Rows of Tasks -->
                        <?php while($job = mysqli_fetch_object($job_results) )  { ?>
                            <tr>
                                <!-- Keep the same -->
                                <td><?php echo $job->job_id; ?></td>
                                
                                <td>
                                    <?php if($job->job_status == 1): ?>
                                        <span class = "has-text-success">Done</span>
                                    <?php elseif($job->job_status == 2): ?>
                                        <span>In Progress</span>
                                    <?php elseif($job->job_status == 3): ?>
                                        <span class = "has-text-danger">Deleted</span>    
                                    <?php endif; ?>
                                </td>
                                <!-- Get title -->
                                <td><?php echo $job->job_title; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $job->job_instructions; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $job->job_deadline; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $job->job_created; ?></td>
                                <!-- Keep the same -->
                                <td>
                                    <a class="<?php if($job->job_status == 1 || $job->job_status == 3) {echo "is-hidden";} ?>" href="./view-one-job?job_id=<?php echo $job->job_id; ?>"><button class="button">See Details</button></a>
                                </td>
                                <td>
                                    <a class="<?php if($admin_form_disabled == "disabled") {echo "is-hidden";} ?>" href="./job-admin.php?job_id=<?php echo $job->job_id; ?>"><button class="button">Admin Panel</button></a>
                                </td>
                                <td>
                                    <a class="<?php if($job->job_status == 3) {echo "is-hidden";} ?>" href="./view-jobs-table.php?job_id=<?php echo $job->job_id; ?>&change_status=3"><button class="button">Delete</button></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

