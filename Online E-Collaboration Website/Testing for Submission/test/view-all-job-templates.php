<?php
// Initialize the session
session_start();
require 'db.php';

// view all job templates
$job_temp_results = mysqli_query($link, 'SELECT * FROM jobTemplate_T');

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View All Job Templates</title>
</head>
<body>
    <section class="hero is-danger is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View All Job Templates
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
    <div class="columns">
        <div class="column is-2">
            <div class="container">
                <?php $current_topic = "job-template"; include "./vertical-menu.php"; ?>
            </div>
        </div>
        <div class="column is-1"></div>
        <div class="column is-8">
            <?php while($job_temp = mysqli_fetch_object($job_temp_results) )  { ?>
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                        <div class="content">
                            <p><strong>Title: <?php echo $job_temp->job_title; ?></strong></p>
                            <p><?php echo $job_temp->job_instructions; ?></p>
                            <p>
                                Status: 
                                
                                <?php 

                                $status = $job_temp->templateStatus_id;

                                if ($status == 1) {
                                    echo "Ready/Activate";
                                }
                                else if ($status == 2) {
                                    echo "Not Ready/Deactivate";
                                }
                                else {
                                    echo "Delete/Hide";
                                }

                                ?>
                            </p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item" href="./view-one-job-template?jobTemplate_id=<?php echo $job_temp->jobTemplate_id; ?>">
                                    See Details
                                </a>
                            </div>
                        </nav>
                        </div>
                    </article>
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
                A SQL statement gets all the job templates in the jobTemplate_T table. Later, Unit A will provide a user_id to narrow down the visible job templates.
                At the moment, all the job templates are visible.
                A while loop generates all the job templates.
                The hyperlink contains the jobTemplate_id which will be used to fetch the correct, singular job template.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>