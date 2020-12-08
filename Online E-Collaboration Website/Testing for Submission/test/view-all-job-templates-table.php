<?php
// Initialize the session
session_start();
require 'db.php';

// this variable is to see if the user is an admin or manager
$admin_form_disabled = "disabled";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $admin_form_disabled = "";   
}

// toggle templateStatus_id of jobTemplate_T
if( isset($_GET['templateStatus_id']) && isset($_GET['jobTemplate_id'])) {
    $current_jobTemplate_id = trim($_GET["jobTemplate_id"]);
    $templateStatus_id = trim($_GET["templateStatus_id"]);

    $jobTemplate_sql = "UPDATE jobTemplate_T SET templateStatus_id = ? WHERE jobTemplate_id = ?";

    if($stmt = mysqli_prepare($link, $jobTemplate_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $templateStatus_id;
        $par2 = $current_jobTemplate_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}


// view all job templates as admin
if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) {
    $job_template_results = mysqli_query($link, 'SELECT * FROM jobTemplate_T');
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Job Template Table</title>
</head>
<body>
    <section class="hero is-danger is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                View A Job Template
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
            <div class="column is-10">
                <div class="box table-container">
                
            <!-- table is created on the condition that the user is an admin 1 or manager 2 -->    
            <?php if ($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) { ?>
                <table class="table is-fullwidth is-striped">
                    <thead>
                        <tr>
                            <th>Template ID</th>
                            <th>Job Title</th>
                            <th>Job Instructions</th>
                            <th>Template Status</th>
                            <th>Edit Template</th>
                            <th>Admin Panel</th>
                            <th>Update Template Status</th>
                            <th>Delete Template</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php } else {} ?>   

                        <!-- if table was created above, the table is then populated -->
                        <?php if ($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2) { 
                                while($template = mysqli_fetch_object($job_template_results) )  { 
                        ?>
                            <tr>
                                <!-- id -->
                                <td><?php echo $template->jobTemplate_id; ?></td>
                                <!-- title -->
                                <td><?php echo $template->job_title; ?></td>
                                <!-- instructions -->
                                <td><?php echo $template->job_instructions; ?></td>
                                <!-- change status to ready, not ready, or deleted -->
                                <td>
                                    <?php if ($template->templateStatus_id == 1): ?>
                                        <span class="has-text-success">Ready</span>
                                    <?php elseif ($template->templateStatus_id == 2): ?>
                                        <span>Not Ready</span>
                                    <?php else: ?>
                                        <span class="has-text-danger">Deleted</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <a href="./view-one-job-template?jobTemplate_id=<?php echo $template->jobTemplate_id; ?>"><button class="button">See Details</button></a>
                                </td>
                                <td>
                                    <a href="./job-template-admin.php?jobTemplate_id=<?php echo $template->jobTemplate_id; ?>"><button class="button">Admin Panel</button></a>
                                </td>
                                <td>
                                    <div class="field is-grouped">
                                        <a href="./view-all-job-templates-table.php?jobTemplate_id=<?php echo $template->jobTemplate_id; ?>&templateStatus_id=1"><button class="button" style="color: hsl(141, 53%, 53%);">Ready</button></a>
                                        <a href="./view-all-job-templates-table.php?jobTemplate_id=<?php echo $template->jobTemplate_id; ?>&templateStatus_id=2"><button class="button">Not Ready</button></a>
                                    </div>
                                </td>
                                <td>
                                    <a href="./view-all-job-templates-table.php?jobTemplate_id=<?php echo $template->jobTemplate_id; ?>&templateStatus_id=3"><button class="button" style="color: hsl(348, 100%, 61%);">Delete</button></a>
                                </td>
                            </tr>
                        <?php } }
                                else {
                                    //if the user is not an admin or manager, the while loop does not run and this message is displayed
                                    $user = $_SESSION['userRole_id'];
                                    echo "User with ID #" . $user . ": You do not have permission to view this page. Please contact an Admin or Manager.";
                                }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
