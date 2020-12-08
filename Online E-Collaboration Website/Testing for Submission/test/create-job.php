<?php
require 'db.php';
// Initialize the session
session_start();

$confirm_user_msg = "";

// get the job templates for the form
$jobTemplate_results = mysqli_query($link, 'SELECT * FROM jobTemplate_T WHERE templateStatus_id = 1');

//get params from form
if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $jobTemplate_id = trim($_POST["template-id"]);
    $job_deadline = trim($_POST["job-deadline"]);
    $job_created = date("Y-m-d H:i:s");

    //get the row from jobTemplate_T that needs to be copied into job_T
    $jobTemplate_row = mysqli_query($link, 'SELECT * FROM jobTemplate_T WHERE jobTemplate_id = ' . $jobTemplate_id);
    if ($jobTemplate_row->num_rows > 0) {
        while($row = $jobTemplate_row->fetch_assoc()) {
            $job_title = $row['job_title'];
            $job_instructions = $row['job_instructions'];
            $templateStatus_id = $row['templateStatus_id'];
        }
    }

    // insert a new row into job_T
    $job_instance_sql = "INSERT INTO job_T(job_status, job_owner, job_title, job_instructions, job_deadline, job_created) VALUES (?,?,?,?,?,?)";
    if($stmt = mysqli_prepare($link, $job_instance_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iissss", $param2, $param3, $param5, $param6, $param8, $param9);
        // Set parameters
        $param2 = 2;
        $param3 = $_SESSION["user_id"];
        // will come from unit A. Currently in the SESSION variable
        $param5 = $job_title;
        $param6 = $job_instructions;
        $param8 = $job_deadline;
        $param9 = $job_created;
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);  
            $confirm_user_msg = "Job successfully created.";
        } else {
            $confirm_user_msg = "Oops! Something went wrong. Please try again later.";
        }
    }
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Create a Job</title>
</head>
<body>
    <section class="hero" style="background-color: rgb(128,0,128);">
        <div class="hero-body">
            <div class="container has-text-centered has-text-white">
            <h1 class="title has-text-white">
                Job Management
            </h1>
            <h2 class="subtitle has-text-white">
                Create a Job
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-2">
                    <div class="container">
                        <?php $current_topic = "job"; include "./vertical-menu.php"; ?>
                    </div>
                </div>
                <div class="column is-1"></div>
                <div class="column is-8">
                    <div class="box">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                        <!--Select Option-->
                        <h1 class="title">Create New Job</h1>
                        <h2 class="subtitle"><?php echo $confirm_user_msg; ?></h2>
                        <hr>
                        <label for="template-id">Select A Job Template</label>
                        <br>
                        <div class="control">
                            <div class="select">
                            <select name="template-id" id="template-id">
                                <?php while($template = mysqli_fetch_object($jobTemplate_results)) { ?>
                                    <option value="<?php echo $template->jobTemplate_id; ?>"><?php echo $template->job_title; ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <br>

                        <label for="job-deadline">Deadline for Job</label>
                        <br>
                        <div class="field">
                            <div class="control">
                                <input type="datetime-local" name="job-deadline" id="job-deadline">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-info">Submit</button>
                            </div>
                        </div>

                    </form>
                    </div>
                </div>
            </div>
            <div class="column-is-1"></div>
        </div>
    </section>
<footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                This page uses a SQL query to populate the area that shows all the possible job templates from our jobTemplate_T table. 
                Once a template is selected and updated with the required information (deadline), we have a SQL query that copies the information
                from our jobTemplate_T that has the same selected template id, and then we copy that information combined with our new information
                to our jobs_T table. 
            </p>
            <br>
        </div>
    </footer>
</body>
</html>
