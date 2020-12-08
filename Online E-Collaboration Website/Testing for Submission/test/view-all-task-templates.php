<?php

session_start();
require 'db.php';

	$result = mysqli_query($link, 'select * from taskTemplate_T');

	// Change task template status by changing its status
	if(isset($_GET['change_status']) && isset($_GET['taskTemplate_id'])) {
	    $update_task = $_GET['taskTemplate_id'];
	    $status_status_id = $_GET['change_status'];

	    $update_task_sql = 'UPDATE taskTemplate_T SET templateStatus_id = ? WHERE taskTemplate_id = ?';

	    if($stmt = mysqli_prepare($link, $update_task_sql)){
	        // Bind variables to the prepared statement as parameters
	        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
	        
	        $par1 = $status_status_id;
	        $par2 = $update_task;

	        mysqli_stmt_execute($stmt);        
	        mysqli_stmt_close($stmt);
	    }
	}

    // view all task templates as an admin
if($_SESSION['user_id'] == 001) {
    $result = mysqli_query($link, 'SELECT * FROM taskTemplate_T');
} else {
    // view all task templates where the SESSION user is listed on a taskTemplateDetails row
    $result = mysqli_query($link, 'SELECT t.* FROM taskTemplate_T t LEFT JOIN taskTemplateDetails_T td ON t.taskTemplate_id = td.taskTemplate_id WHERE td.userRole_id = ' . $_SESSION['user_id'] );
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View All Task Templates</title>
</head>
<body>
	<section class="hero is-warning is-bold">
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
        <div class="column is-2">
            <div class="container">
                <?php $current_topic = "task-template"; include "./vertical-menu.php"; ?>
            </div>
        </div>
        <div class="column is-1"></div>
        <div class="column is-8">
            <?php while($tTemp = mysqli_fetch_object($result) )  { ?>
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                        <div class="content">
                        	<p><strong>Title: <?php echo $tTemp->task_title; ?></strong></p>
                            <p><strong>Template ID: <?php echo $tTemp->taskTemplate_id; ?></strong></p>
                            <p><strong>Policy ID: <?php echo $tTemp->policy_id; ?></strong></p>
                            <p><strong>Task Type:</strong> <span class="content is-hidden"><?php echo $tTemp->task_type ?></span>
                            	<?php if ($tTemp->task_type== 1): ?>
                                    <span><strong>Urgent</span></strong>
                                <?php elseif ($tTemp->task_type== 2): ?>
                                    <span><strong> Normal</span></strong>
                                <?php endif; ?>
                            </p>
                            <p><strong>Task Instructions: <?php echo $tTemp->task_instructions; ?></strong></p>
                            <p><strong>Status:</strong> <span class="content is-hidden"><?php echo $tTemp->templateStatus_id ?></span>
                            	<?php if ($tTemp->templateStatus_id== 1): ?>
                                    <span><strong> Ready</span></strong>
                                <?php elseif ($tTemp->templateStatus_id== 2): ?>
                                    <span><strong> Not Ready</span></strong>
                                <?php elseif ($tTemp->templateStatus_id== 3): ?>
                                    <span><strong> Deleted</span></strong>
                                    <?php endif; ?>
                            </p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item <?php if($tTemp->templateStatus_id == 3) {echo "is-hidden";} ?>" href="./view-one-task-template?taskTemplate_id=<?php echo $tTemp->taskTemplate_id; ?>">
                                    See Details
                                </a>
                            </div>
                            <div class="level-left">
                                <a href="./view-all-task-templates.php?taskTemplate_id=<?php echo $tTemp->taskTemplate_id; ?>&change_status=3"><button class="button">Delete</button></a>
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
                A SQL statement gets all the task templates in the taskTemplate_T table. If the user is an admin, all the task templates are visible. A while loop generates all the task templates for that SQL statement. The hyperlink contains the taskTemplate_id which will be used to fetch the correct, singular task template. The delete button changes the status of the task template to deleted. If the task template is deleted, the hyperlink to view the task template is hidden.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>

