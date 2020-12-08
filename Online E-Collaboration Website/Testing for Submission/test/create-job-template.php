<?php
require 'db.php';
// Initialize the session
session_start();

$job_title = $job_instructions = $templateStatus_id = "";
$job_title_err = $job_instructions_err = $templateStatus_id_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate entries
    if(empty(trim($_POST["job_title"]))){
        $job_title_err = "Please enter a title.";
    } 
    else if(empty(trim($_POST["job_instructions"]))){
        $job_instructions_err = "Please enter job template instructions.";
    } 
    else if(empty(trim($_POST["templateStatus_id"]))){
        $templateStatus_id_err = "Please enter job template status id.";
    } 
    else {
    // Prepare a insert into statement
    $sql = "INSERT INTO jobTemplate_T (job_title, job_instructions, templateStatus_id) VALUES (?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)) { 
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $par1, $par2, $par3);
        // Set parameters
        $par1 = trim($_POST["job_title"]);
        $par2 = trim($_POST["job_instructions"]);
        $par3 = trim($_POST["templateStatus_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);   
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Create a Job Template</title>
</head>
<body>
<section class="hero is-danger is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                Create a Job Template
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns is-mobile is-centered">
                <div class="column is-2">
                    <div class="container">
                        <?php $current_topic = "job-template"; include "./vertical-menu.php"; ?>
                    </div>
                </div>
                <div class="column is-1"></div>
                <div class="column is-8">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div <?php echo (!empty($job_title_err)) ? 'has-error' : ''; ?>">
                            <label for="job_title">Job Title</label>
                            <input type="text" class="input is-info" name="job_title" value="<?php echo $job_title;?>">
                            <span class="has-text-danger"><?php echo $job_title_err; ?></span>
                        </div> 
            
                            <br>
                            <p>
                            <div class="control" <?php echo (!empty($templateStatus_id_err)) ? 'has-error' : ''; ?>">
                                <legend>Job Status</legend>
                                    <input type="radio" name="templateStatus_id" value="1">
                                    <label for="job_type">Ready</label>
                                        <br>
                                    <input type="radio" name="templateStatus_id" value="2">
                                    <label for="job_type">Not Ready</label>
                                        <br>
                                    <input type="radio" name="templateStatus_id" value="3">
                                    <label for="job_type">Inactive</label>
                                        <br>
                                <span class="has-text-danger"><?php echo $templateStatus_id_err; ?></span>
                                </fieldset>
                            </div>
                            <br>
                        <div <?php echo (!empty($job_instructions_err)) ? 'has-error' : ''; ?>">
                            <label for="job_instructions">Job Instructions:</label>
                                <br>
                            <textarea name="job_instructions" class="textarea is-info is-small" value="<?php echo $job_instructions;?>" cols="30" rows="10"></textarea>
                            <span class="has-text-danger"><?php echo $job_instructions_err; ?></span>
                        </div>
                            <br>
                            <p>
                            <input class="button is-rounded is-pulled-right is-primary" type="submit" value="Create Job Template">
                        </form>
                    </div>
                </div>
                <div class="column is-1"></div>
            </div>
        </section>
        <footer>
            <div class="container is-fluid has-background-dark has-text-white">
                <br>
                <p class="title has-text-white">Page Protocol</p>
                <p class="content">
                    This form allows the user to generate a job template using text boxes and a radio button. 
                    Each text box and the radio button must be inputted, 
                    as the jobTemplate_T table cannot accept null values. 
                    Upon submission of this form, 
                    a new job template is generated in the jobTemplate_T and a jobTemplate_ID is auto-incremented to ensure each template is unique.
                </p>
                <br>
            </div>
        </footer>
    </body>
    </html>
