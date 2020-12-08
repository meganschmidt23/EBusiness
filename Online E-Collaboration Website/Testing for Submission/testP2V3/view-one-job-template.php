<?php
// Initialize the session
session_start();
require 'db.php';

// get jobTemplate_id from $_GET from view-all-job-templates.php
if( !empty($_GET["jobTemplate_id"]) ) {
    $current_jobTemplate_id = trim( $_GET["jobTemplate_id"] );
}

// toggle templateStatus_id of jobTemplate_T
if( isset($_GET['newTemplateStatus_id']) && isset($_GET['jobTemplate_id'])) {

    $current_jobTemplate_id = trim($_GET["jobTemplate_id"]);
    $newTemplateStatus_id = trim($_GET["newTemplateStatus_id"]);

    $status_sql = "UPDATE jobTemplate_T SET templateStatus_id = ? WHERE jobTemplate_id = ?";

    if($stmt = mysqli_prepare($link, $status_sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);

        $par1 = $newTemplateStatus_id;
        $par2 = $current_jobTemplate_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

$jobTemplate_results = mysqli_query($link, 'SELECT * FROM jobTemplate_T WHERE jobTemplate_id = ' . $current_jobTemplate_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>View Job Template</title>
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
                    <?php include "./vertical-menu.php"; ?>
                </div>
            </div>
            <div class="column is-1"></div>
            <div class="column is-8">
            
            <?php while($job_temp = mysqli_fetch_object($jobTemplate_results) )  { ?>
                
                <div class="box">
                    <h1 class="title is-6">
                        Job Template Title: 
                        <span class="is-uppercase">
                            <?php echo $job_temp->job_title; ?>
                        </span>
                    </h1>
                    
                    <hr>
                    <h2 class="title is-6">Job Template Instructions:</h2>
                    <p class="content">
                        <?php echo $job_temp->job_instructions; ?>
                    </p>
                    <hr>
                    
                    <h2 class="title is-6">Current Job Template Status:</h2>
                        <p> 

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

                    <br>
                    
                    <form action="./view-one-job-template.php" method="GET">
                        
                        <!-- Radio Buttons -->
                        <p>Edit Job Template Status: </p>
                        <div class="field">
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="newTemplateStatus_id" value="1">
                                    Ready/Activate
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="newTemplateStatus_id" value="2">
                                    Not Ready/Deactivate
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="newTemplateStatus_id" value="3">
                                    Delete/Hide
                                </label>

                            </div>
                        </div>

                        <input type="hidden" name="jobTemplate_id" value="<?php echo $current_jobTemplate_id; ?>">

                        <!-- Submit Button -->
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-info">Submit</button>
                            </div>
                        </div>
                    </form>

                    <hr>
                    <h2 class="title is-6">Task Admin:</h2>
                    <a href="./job-template-admin?jobTemplate_id=<?php echo $job_temp->jobTemplate_id; ?>"><button class="button is-success">View & Change</button></a>
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
                A SQL statement fetches data from jobTemplate_T based on the jobTemplate_id passed as a query parameter.
                For templateStatus_id, there is a form to toggle between 1, 2, and 3.
                There is a SQL query to convert the id number into legible Ready/Activate, Not Ready/Inactivate, and Delete/Hide text.
                There is a hidden input in the form to preserve the jobTemplate_id that was passed as a query parameter.
                The last feature of the page is the task admin link.
                At the moment, this button is visible to all users.
                In the future, Unit A will provide the role_id alongside the user_id; we can then hide/display the task admin based on that role_id.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>