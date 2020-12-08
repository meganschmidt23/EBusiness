<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin or manager
$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

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
    $task_results = mysqli_query($link, 
    'SELECT t.*, u.user_fullname, p.policy_title
    FROM task_T t LEFT JOIN user_T u
        ON t.task_owner = u.user_id
    LEFT JOIN policy_T p
        ON t.policy_id = p.policy_id');
} else {
    // view all tasks where the SESSION user is listed on a taskDetails row
    $task_results = mysqli_query($link, 
    'SELECT DISTINCT t.*, u.user_fullname, p.policy_title
    FROM task_T t LEFT JOIN taskDetails_T td 
        ON t.task_id = td.task_id
    LEFT JOIN user_T u
        ON t.task_owner = u.user_id
    LEFT JOIN policy_T p
        ON t.policy_id = p.policy_id
    WHERE td.user_id = ' . $_SESSION['user_id']);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Task Table</title>
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
            <div class="column is-10">
                <div class="box table-container">
                <table class="table is-striped">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Policy ID</th>
                            <th>Task Type</th>
                            <th>Task Status</th>
                            <th>Task Owner</th>
                            <th>Task Title</th>
                            <th>Task Instructions</th>
                            <th>Task Created</th>
                            <th>Task Deadline</th>
                            <th>Edit Tasks</th>
                            <th>Admin Panel</th>
                            <th>Delete Task</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- While Loop to Generate Rows of Tasks -->
                        <?php while($task = mysqli_fetch_object($task_results) )  { ?>
                            <tr>
                                <!-- Keep the same -->
                                <td><?php echo $task->task_id; ?></td>
                                <!-- Change policy_id to a string -->
                                <td><?php echo $task->policy_title; ?></td>
                                <!-- Change task_type to either normal and urgent -->
                                <td>
                                    <?php if($task->task_type == 1): ?>
                                        <span>Urgent</span>
                                    <?php else: ?>
                                        <span>Normal</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Change task_type to approved, in-progress, rejected, or deleted -->
                                <td>
                                    <?php if ($task->task_status == 1): ?>
                                        <span class="has-text-success">Approved</span>
                                    <?php elseif ($task->task_status == 2): ?>
                                        <span>In-Progress</span>
                                    <?php elseif ($task->task_status == 3): ?>
                                        <span>Rejected</span>
                                    <?php else: ?>
                                        <span class="has-text-danger">Deleted</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Get task owner's name -->
                                <td><?php echo $task->user_fullname; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $task->task_title; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $task->task_instructions; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $task->task_created; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $task->task_deadline; ?></td>
                                <td>
                                    <a class="<?php if($task->task_status == 4 || $task->task_status == 1) {echo "is-hidden";} ?>" href="./view-one-task?task_id=<?php echo $task->task_id; ?>"><button class="button">See Details</button></a>
                                </td>
                                <td>
                                    <a class="<?php echo $admin_form_disabled;?>" href="./task-admin.php?task_id=<?php echo $task->task_id; ?>"><button class="button">Admin Panel</button></a>
                                </td>
                                <td>
                                    <a href="./view-tasks-table.php?task_id=<?php echo $task->task_id; ?>&change_status=4"><button class="button">Delete</button></a>
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