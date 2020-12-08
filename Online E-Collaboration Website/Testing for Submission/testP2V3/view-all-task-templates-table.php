<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin or manager
$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

// toggle templateStatus_id of taskTemplate_T
if( isset($_GET['templateStatus_id']) && isset($_GET['taskTemplate_id'])) {
    $current_taskTemplate_id = trim($_GET["taskTemplate_id"]);
    $templateStatus_id = trim($_GET["templateStatus_id"]);

    $taskTemplate_sql = "UPDATE taskTemplate_T SET templateStatus_id = ? WHERE taskTemplate_id = ?";

    if($stmt = mysqli_prepare($link, $taskTemplate_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $templateStatus_id;
        $par2 = $current_taskTemplate_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}


// view all task templates as an admin
if($_SESSION['userRole_id'] == 1) {
    $task_template_results = mysqli_query($link, 'SELECT * FROM taskTemplate_T');
} else {
    // view all task templates where the SESSION user is listed on a taskTemplateDetails row
    $task_template_results = mysqli_query($link, 'SELECT t.* FROM taskTemplate_T t LEFT JOIN taskTemplateDetails_T td ON t.taskTemplate_id = td.taskTemplate_id WHERE td.userRole_id = ' . $_SESSION['user_id'] );
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Task Template Table</title>
</head>
<body>
    <section class="hero is-warning is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View A Task Template
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
            <div class="column-is-1"></div>
            <div class="column is-four-fifths">
                <div class="box">
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Template ID</th>
                            <th>Policy ID</th>
                            <th>Template Type</th>
                            <th>Template Status</th>
                            <th>Template Title</th>
                            <th>Template Instructions</th>
                            <th>Edit Template</th>
                            <th>Admin Panel</th>
                            <th>Delete Template</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- While Loop to Generate Rows of Templates -->
                        <?php while($template = mysqli_fetch_object($task_template_results) )  { ?>
                            <tr>
                                <!-- Keep the same -->
                                <td><?php echo $template->taskTemplate_id; ?></td>
                                <!-- Change policy_id to a string -->
                                <td><?php echo $template->policy_id; ?></td>
                                <!-- Change task_type to either normal and urgent -->
                                <td>
                                    <?php if($template->task_type == 1): ?>
                                        <span>Urgent</span>
                                    <?php else: ?>
                                        <span>Normal</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Change task_type to approved, in-progress, rejected, or deleted -->
                                <td>
                                    <?php if ($template->templateStatus_id == 1): ?>
                                        <span class="has-text-success">Ready</span>
                                    <?php elseif ($template->templateStatus_id == 2): ?>
                                        <span>Not Ready</span>
                                    <?php else: ?>
                                        <span class="has-text-danger">Deleted</span>
                                    <?php endif; ?>
                                </td>
                                <!-- Keep the same -->
                                <td><?php echo $template->task_title; ?></td>
                                <!-- Keep the same -->
                                <td><?php echo $template->task_instructions; ?></td>

                                <td>
                                    <a class="<?php if($template->templateStatus_id == 3) {echo "is-hidden";} ?>" href="./view-one-task-template?taskTemplate_id=<?php echo $template->taskTemplate_id; ?>"><button class="button">See Details</button></a>
                                </td>
                                <td>
                                    <a class="<?php echo $admin_form_disabled;?>" href="./template-admin.php?taskTemplate_id=<?php echo $template->taskTemplate_id; ?>"><button class="button">Admin Panel</button></a>
                                </td>
                                <td>
                                    <a href="./view-all-task-templates-table.php?taskTemplate_id=<?php echo $template->taskTemplate_id; ?>&templateStatus_id=3"><button class="button">Delete</button></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="column-is-1"></div>
        </div>
    </section>
</body>
</html>
