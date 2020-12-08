<?php
require 'db.php';
// Initialize the session
session_start();


$task_title = $policy_id = $task_type = $task_instructions = $templateStatus_id = "";
$task_title_err = $policy_id_err = $task_type_err = $task_instructions_err = $templateStatus_id_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate entries
    if(empty(trim($_POST["task_title"]))){
        $task_title_err = "Please enter a title.";
    } else if(empty(trim($_POST["policy_id"]))){
        $policy_id_err = "Please enter a task template policy.";
    } else if(empty(trim($_POST["task_type"]))){
        $task_type_err = "Please enter a task type.";
    } else if(empty(trim($_POST["task_instructions"]))){
        $task_instructions_err = "Please enter task template instructions.";
    } else if(empty(trim($_POST["templateStatus_id"]))){
        $templateStatus_id_err = "Please enter task template status id.";
    } 
    else {
    // Prepare a insert into statement
    $sql = "INSERT INTO taskTemplate_T (policy_id, task_type, task_title, task_instructions, templateStatus_id) VALUES (?, ?, ?, ?, ?)";

    if($stmt = mysqli_prepare($link, $sql)) { 
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iissi", $par1, $par2, $par3, $par4, $par5);
        // Set parameters
        $par1 = trim($_POST["policy_id"]);
        $par2 = trim($_POST["task_type"]);
        $par3 = trim($_POST["task_title"]);
        $par4 = trim($_POST["task_instructions"]);
        $par5 = trim($_POST["templateStatus_id"]);

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
    <title>Create a Task Template</title>
</head>
<body>
<section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                Create a Task Template
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns is-mobile is-centered">
                <div class="column is-half">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div <?php echo (!empty($task_title_err)) ? 'has-error' : ''; ?>">
                            <label for="task_title">Title</label>
                            <input type="text" class="input is-info" name="task_title" value="<?php echo $task_title;?>">
                            <span class="has-text-danger"><?php echo $task_title_err; ?></span>
                        </div> 
                            <br>
                            <p> 
                        <div>       
                            <label for="policy_id">Choose a Policy:</label>
                            <select name="policy_id" class="select is-info" value="<?php echo $policy_id;?>">">
                                <option value="1">Policy 1</option>
                                <option value="2">Policy 2</option>
                                <option value="3">Policy 3</option>
                            </select>
                        </div>
                        <div class="control" <?php echo (!empty($task_type_err)) ? 'has-error' : ''; ?>">
                            <legend>Task Type</legend>
                                <input type="radio" name="task_type" value="1">
                                <label for="task_type">Urgent</label>
                                    <br>
                                <input type="radio" name="task_type" value="2">
                                <label for="task_type">Normal</label>
                                <span class="has-text-danger"><?php echo $task_type_err; ?></span>
                                </fieldset>

                        </div>
                            <br>
                            <p>
                            <div class="control" <?php echo (!empty($templateStatus_id_err)) ? 'has-error' : ''; ?>">
                                <legend>Task Status</legend>
                                    <input type="radio" name="templateStatus_id" value="1">
                                    <label for="task_type">Ready</label>
                                        <br>
                                    <input type="radio" name="templateStatus_id" value="2">
                                    <label for="task_type">Not Ready</label>
                                        <br>
                                    <input type="radio" name="templateStatus_id" value="3">
                                    <label for="task_type">Inactive</label>
                                        <br>
                                <span class="has-text-danger"><?php echo $templateStatus_id_err; ?></span>
                                </fieldset>
                            </div>
                            <br>
                        <div <?php echo (!empty($task_instructions_err)) ? 'has-error' : ''; ?>">
                            <label for="task_instructions">Task Instructions:</label>
                                <br>
                            <textarea name="task_instructions" class="textarea is-info is-small" value="<?php echo $task_instructions;?>" cols="30" rows="10"></textarea>
                            <span class="has-text-danger"><?php echo $task_instructions_err; ?></span>
                        </div>
                            <br>
                            <p>
                            <input class="button is-rounded is-pulled-right is-primary" type="submit" value="Create Task Template">
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
    </html>
