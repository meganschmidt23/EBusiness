<?php

session_start();
require 'db.php';

	$result = mysqli_query($link, 'select * from taskTemplate_T');

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View All Task Templates</title>
</head>
<body>
	<section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View All Task Templates
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
    <div class="columns">
        <div class="column is-half is-offset-one-quarter">
            <?php while($tTemp = mysqli_fetch_object($result) )  { ?>
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                        <div class="content">
                        	<p><strong>Title: <?php echo $tTemp->task_title; ?></strong></p>
                            <p><strong>Template ID: <?php echo $tTemp->taskTemplate_id; ?></strong></p>
                            <p><strong>Policy ID: <?php echo $tTemp->policy_id; ?></strong></p>
                            <p><strong>Task Type: <?php echo $tTemp->task_type; ?></strong></p>
                            <p><strong>Task Instructions: <?php echo $tTemp->task_instructions; ?></strong></p>
                            <p>Status: <time><?php echo $tTemp->templateStatus_id ?></time></p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item" href="./view-one-task-template?taskTemplate_id=<?php echo $tTemp->taskTemplate_id; ?>">
                                    See Details
                                </a>
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
                A SQL statement gets all the task templates in the taskTemplate_T table. Later, Unit A will provide a user_id to narrow down the visible task templates.
                At the moment, all the task templates are visible.
                A while loop generates all the task templates.
                The hyperlink contains the taskTemplate_id which will be used to fetch the correct, singular task.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>
