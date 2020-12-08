<?php
// Initialize the session
session_start();
require 'db.php';

$user_id=001;

// Change task status by changing its status
if(isset($_GET['change_status']) && isset($_GET['job_id'])) {
    $update_job = $_GET['job_id'];
    $status_status_id = $_GET['change_status'];

    $update_job_sql = 'UPDATE job_T SET jobstatus = ? WHERE job_id = ?';

    if($stmt = mysqli_prepare($link, $update_job_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $status_status_id;
        $par2 = $update_job;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view all jobs as an admin
if($_SESSION['user_id'] == 001) {
    $job_results = mysqli_query($link, 'SELECT * FROM job_T');
} else {
    // view all jobs where the SESSION user is job_owner
    $job_results = mysqli_query($link, 'SELECT * FROM job_T WHERE job_owner = ' . $_SESSION['user_id'] );
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View All Jobs</title>
    <style>
        a.disabled {
            pointer-events: none;
        }
    </style>
</head>
<body>
    <?php include "./navbar.php"; ?>
    <section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View All Jobs
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
    <div class="columns">
        <div class="column is-half is-offset-one-quarter">
            <?php while($job = mysqli_fetch_object($job_results) )  { ?>
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                        <div class="content">
                            <p>
                                <strong>
                                    Title: <span class="is-uppercase"><?php echo $job->job_title; ?></span>
                                    <?php if ($job->job_status == 1): ?>
                                        <span> (Done)</span>
                                    <?php elseif ($job->job_status == 2): ?>
                                        <span> (In-Progress)</span>
                                    <?php elseif ($job->job_status == 4): ?>
                                        <span> (Paused)</span>
                                    <?php else: ?>
                                        <span> (Deleted)</span>
                                    <?php endif; ?>
                                </strong>
                            </p>
                            <p><?php echo $job->job_instructions; ?></p>
                            <p>Deadline: <time><?php echo $job->job_deadline; ?></time></p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item <?php if($job->job_status == 3) {echo "is-hidden";} ?>" href="./view-one-job?job_id=<?php echo $job->job_id; ?>">
                                    See Details
                                </a>
                            </div>
                            <div class="level-left">
                                <a href="./view-all-jobs.php?job_id=<?php echo $job->job_id; ?>&change_status=3"><button class="button">Delete</button></a>
                            </div>
                        </nav>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
    </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                A SQL statement gets all the jobs in the job_T table. Later, Unit A will provide a user_id to narrow down the visible jobs.
                At the moment, all jobs are visible.
                A while loop generates all the jobs.
                The hyperlink contains the job_id which will be used to fetch the correct, singular job.
                The delete button changes the status of the job to deleted.
                If the job is deleted, the hyperlink to view the job is hidden.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>