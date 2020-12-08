<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin
$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

// get task_id from $_GET from view-all-task.php
if( !empty($_GET["task_id"]) ) {
    $current_task_id = trim( $_GET["task_id"] );
}

// toggle taskPart_status of taskDetails_T
if( isset($_GET['taskPart_status']) && isset($_GET['task_id'])) {
    $current_task_id = trim($_GET["task_id"]);
    $taskPart_status = trim($_GET["taskPart_status"]);

    $taskPart_sql = "UPDATE taskDetails_T SET taskPart_status = ? WHERE task_id = ? AND user_id = ?";

    if($stmt = mysqli_prepare($link, $taskPart_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $taskPart_status;
        $par2 = $current_task_id; 
        $par3 = $_SESSION['user_id'];

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }

    // update the tasks status based on the task details

    // if there is a task detail that was rejected (value of 2) than the task should have status rejected (value of 3)
    // if SUM = COUNT than task is approved 
    // if SUM != Count than task is still in-progress
    $check_reject_status = mysqli_query($link, 'SELECT COUNT(*) as count FROM taskDetails_T WHERE taskPart_status = 2 AND task_id = ' . $current_task_id);
    $data_count = $check_reject_status->fetch_assoc();
    if ($data_count['count'] > 0) {
        $status_id = 3;
    }
    else {
        $tasksCompleted_result = mysqli_query($link, "SELECT SUM(taskPart_status) as tasks_completed, COUNT(*) as number_of_total_tasks FROM taskDetails_T WHERE task_id = " . $current_task_id);
        if ($tasksCompleted_result->num_rows > 0) {
            while($row = $tasksCompleted_result->fetch_assoc()) {
                $tasksCompleted_number = $row['tasks_completed'];
                $total_number_of_tasks = $row['number_of_total_tasks'];
                if($tasksCompleted_number != $total_number_of_tasks) {
                    // the task is still in progress then
                    $status_id = 2;
                } else {
                    // the task is completed and approved
                    $status_id = 1;
                }
            }
        }
    }

    $status_sql = "UPDATE task_T SET task_status = ? WHERE task_id = ?";

    if($stmt = mysqli_prepare($link, $status_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $status_id;
        $par2 = $current_task_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view the one task
$task_results = mysqli_query($link, 'SELECT * FROM task_T WHERE task_id = ' . $current_task_id);

// get the task data associated with this task and user
$taskDetails_results = mysqli_query($link, 'SELECT DISTINCT data_id FROM taskDetails_T WHERE task_id = ' . $current_task_id . ' AND user_id = ' . $_SESSION['user_id']);


// get the current taskPart_status id to display to the user
// $taskPart_results = mysqli_query($link, 'SELECT taskPart_status FROM taskDetails_T WHERE task_id = ' . $current_task_id . ' AND user_id = ' . $_SESSION['user_id']);

// if ($taskPart_results->num_rows > 0) {
//     while($row = $taskPart_results->fetch_assoc()) {
//         $current_statusPart_status = $row['taskPart_status'];
//     }
// }
// // convert the taskPart_status id into a legible text
// if($current_statusPart_status == 0) {
//     $current_statusPart_status = "In-Progress";
// } else {
//     $current_statusPart_status = "Completed";
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View Task</title>
</head>
<body>
    <section class="hero is-info is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View A Task
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
                    <h1 class="title is-6">
                        Task Title: <span class="is-uppercase"><?php echo $task->task_title; ?><span>
                        <?php if ($task->task_status == 1): ?>
                            <span> (Approved)</span>
                        <?php elseif ($task->task_status == 2): ?>
                            <span> (In-Progress)</span>
                        <?php elseif ($task->task_status == 3): ?>
                            <span> (Rejected)</span>
                        <?php elseif ($task->task_status == 4): ?>
                            <span> (Deleted)</span>
                        <?php else: ?>
                            <span> (Paused)</span>
                        <?php endif; ?>
                    </h1>
                    <hr>
                    <h2 class="title is-6">Task Instructions:</h2>
                    <p class="content">
                        <?php echo $task->task_instructions; ?>
                    </p>
                    <hr>
                    <figure class="image is-16by9">
                        <iframe src="./task-data.php?task_id=<?php echo $current_task_id; ?>" class="has-ratio" width="640"></iframe>
                    </figure>
                    <hr>
                    <h2 class="title is-6">Task Actions:</h2>
                    <form action="./view-one-task.php" method="GET">
                        <!-- Radio Buttons -->
                        <br>
                        <p>Change Task Status: </p>
                        <div class="field">
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="taskPart_status" value="0">
                                    In-Progress
                                </label>
                                
                                <label class="radio">
                                    <input type="radio" name="taskPart_status" value="1">
                                    Completed
                                </label>

                                <label class="radio">
                                    <input type="radio" name="taskPart_status" value="2">
                                    Rejected
                                </label>

                            </div>
                        </div>

                        <input type="hidden" name="task_id" value="<?php echo $current_task_id; ?>">

                        <br>
                        <!-- Submit Button -->
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-info">Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <h2 class="title is-6">Task Admin:</h2>
                    <a href="./task-admin.php?task_id=<?php echo $current_task_id; ?>"><button class="button is-success" <?php echo $admin_form_disabled;?>>View & Change</button></a>
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
                A SQL statement fetches data from task_T based on the task_id passed as a query parameter.
                A SQL statement fetches the appropriate taskDetails_T row based on task_id and user_id.
                Most of the data on this page comes from those two queries.
                For task data, there is an iframe which will be passed the correct url to the form or file page.
                For task actions, this is a form to toggle taskPart_status between 0, 1, or 2 - in-progress, completed, or rejected.
                After submitting this form, a SQL query updates the task_status attribute in the task_T if need be.
                There are several SQL queries to convert id numbers into legible in-progress/completed text.
                There is a hidden input in the task actions form to preserve the task_id that was passed as a query parameter.
                The last feature of the page is the task admin link.
                Onload, the page checks to see if the session variable is an admin; depending on the variable, the button will be enabled or disabled.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>