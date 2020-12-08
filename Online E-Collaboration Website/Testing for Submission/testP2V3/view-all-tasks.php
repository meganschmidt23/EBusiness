<?php
// Initialize the session
session_start();
require 'db.php';

// Change task status by changing its status
if(isset($_GET['change_status']) && isset($_GET['task_id'])) {
    $update_task = $_GET['task_id'];
    $status_status_id = $_GET['change_status'];

    $update_task_sql = 'UPDATE task_T SET task_status = ? WHERE task_id = ?';

    if($stmt = mysqli_prepare($link, $update_task_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $status_status_id;
        $par2 = $update_task;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view all tasks as an admin
if($_SESSION['userRole_id'] == 1) {
    $task_results = mysqli_query($link, 'SELECT * FROM task_T');
} else {
    // view all tasks where the SESSION user is listed on a taskDetails row
    $task_results = mysqli_query($link, 'SELECT t.* FROM task_T t LEFT JOIN taskDetails_T td ON t.task_id = td.task_id WHERE td.user_id = ' . $_SESSION['user_id']);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View All Tasks</title>
    <style>
        a.disabled {
            pointer-events: none;
        }
    </style>
</head>
<body>
    <section class="hero is-info is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View All Tasks
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
    <div class="columns">
        <div class="column is-2">
            <div class="container">
                <?php include "./vertical-menu.php"; ?>
            </div>
        </div>
        <div class="column is-1"></div>
        <div class="column is-8">
            <?php while($task = mysqli_fetch_object($task_results) )  { ?>
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                        <div class="content">
                            <p>
                                <strong>
                                    Title: <span class="is-uppercase"><?php echo $task->task_title; ?></span>
                                    <?php if ($task->task_status == 1): ?>
                                        <span> (Approved)</span>
                                    <?php elseif ($task->task_status == 2): ?>
                                        <span> (In-Progress)</span>
                                    <?php elseif ($task->task_status == 3): ?>
                                        <span> (Rejected)</span>
                                    <?php else: ?>
                                        <span> (Deleted)</span>
                                    <?php endif; ?>
                                </strong>
                            </p>
                            <p>
                                <?php if ($task->task_status == 1): ?>
                                    <span class="is-uppercase is-danger">URGENT TASK</span>
                                <?php endif; ?>
                            </p>
                            <p><?php echo $task->task_instructions; ?></p>
                            <p>Deadline: <time><?php echo $task->task_deadline; ?></time></p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                <a class="level-item <?php if($task->task_status == 4 || $task->task_status == 1) {echo "is-hidden";} ?>" href="./view-one-task?task_id=<?php echo $task->task_id; ?>">
                                    See Details
                                </a>
                            </div>
                            <div class="level-left">
                                <a href="./view-all-tasks.php?task_id=<?php echo $task->task_id; ?>&change_status=4"><button class="button">Delete</button></a>
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
                A SQL statement gets all the tasks in the task_T table where the user is the owner of the task or listed under the task's details.
                If the user is an admin, all the tasks are visible.
                A while loop generates all the tasks for that SQL statement.
                The hyperlink contains the task_id which will be used to fetch the correct, singular task.
                The delete button changes the status of the task to deleted.
                If the task is deleted, the hyperlink to view the task is hidden.
                If the task is completed, the hyperlink to view the task is also hidden.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>