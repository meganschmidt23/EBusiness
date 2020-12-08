<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin
//$admin_form_disabled = "disabled";
$admin_form_disabled = "";

if($_SESSION['userRole_id'] == 001 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

// get taskTemplate_id from $_GET from view-all-task-templates.php
if( !empty($_GET["taskTemplate_id"]) ) {
    $current_task_template_id = trim( $_GET["taskTemplate_id"] );
}

// HARD CODED A USER ID. THIS WILL BE A SESSION VARIABLE FROM UNIT A    
$userRole_id = $_SESSION['user_id'];

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
        //$par3 = $userRole_id;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view the one template
$taskTemplate_results = mysqli_query($link, 'SELECT * FROM taskTemplate_T WHERE taskTemplate_id = ' . $current_task_template_id);

// get the task data associated with this task and user
$taskDetails_results = mysqli_query($link, 'SELECT DISTINCT dataType_id FROM taskTemplateDetails_T WHERE taskTemplate_id = ' . $current_task_template_id . ' AND userRole_id = ' . $userRole_id);


// get the current templateStatus_id to display to the user
$templateStatus_results = mysqli_query($link, 'SELECT templateStatus_id FROM taskTemplate_T WHERE taskTemplate_id = ' . $current_task_template_id);

if ($templateStatus_results->num_rows > 0) {
    while($row = $templateStatus_results->fetch_assoc()) {
        $current_status_template_status = $row['templateStatus_id'];
    }
}
// convert the templateSstatus_id into a legible text
if($current_status_template_status == 1) {
    $current_status_template_status = "Ready";
    } else if($current_status_template_status == 2) {
    $current_status_template_status = "Not Ready";
        }  else {
        $current_status_template_status = "Deleted";
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View Task Template</title>
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
            <div class="column is-1"></div>
            <div class="column is-8">
            <?php while($task = mysqli_fetch_object($taskTemplate_results) )  { ?>
                <div class="box">
                    <h1 class="title is-6">Task Template Title: <span class="is-uppercase"><?php echo $task->task_title; ?><span></h1>
                    <hr>
                    <h2 class="title is-6">Task Template Instructions:</h2>
                    <p class="content">
                        <?php echo $task->task_instructions; ?>
                    </p>
                    <!--<hr>
                    <h2 class="title is-6">Task Data (Unit CD PLACEHOLDER):</h2>
                    <?php while($data = mysqli_fetch_object($taskDetails_results) )  { ?>
                        <div class="columns">
                            <div class="column is-one-third">
                                <a><?php echo $data->dataType_id; ?></a>
                            </div>
                            <div class="column is-three-quarters is-offset-3">
                                <div class="buttons">
                                    <button class="button is-primary" style="float:right;">Upload</button>
                                    <button class="button is-primary" style="float:right;">Download</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>-->
                    
                    <h2 class="title is-6"><strong>Task Template Actions:</strong></h2>
                    <form action="./view-one-task-template.php" method="GET">
                        <!-- Radio Buttons -->
                        <p><strong>Current Task Template Status: </strong><?php echo $current_status_template_status; ?></p>
                        <br>
                        <p><strong>Change Task Template Status:</strong> </p>
                        <div class="field">
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="templateStatus_id" value="1">
                                    Ready
                                </label>
                                
                                <label class="radio">
                                    <input type="radio" name="templateStatus_id" value="2">
                                    Not Ready
                                </label>
                                <label class="radio">
                                    <input type="radio" name="templateStatus_id" value="3">
                                    Deleted
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="taskTemplate_id" value="<?php echo $current_task_template_id; ?>">

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
                    <a href="./template-admin.php?taskTemplate_id=<?php echo $current_task_template_id; ?>"><button class="button is-success" <?php echo $admin_form_disabled;?>>View & Change</button></a>
                </div>
            <?php } ?>
            </div>
        </div>
        <div class="column is-1"></div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                A SQL statement fetches data from taskTemplate_T based on the taskTemplate_id passed as a query parameter.
                The task template status portion of the page allows the user to toggle templateStatus_id between "Ready" (1), "Not-Ready" (2), and "Deleted" (3).
                Additionally, for convenieince an SQL query converts this templates current templateStatus_id number into the afforementioned legible text Ready, Not-Ready, and Deleted for the user.
                There is a hidden input in the task template actions form to preserve the taskTemplate_id that was passed as a query parameter.
                The last feature of the page is the task admin link. At the moment, this button is visible to all users. In the future, Unit A will provide the userRole_id alongside the user_id; we can then hide/display the task admin based on that userRole_id.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>
