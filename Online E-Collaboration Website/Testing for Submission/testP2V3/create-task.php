<?php
require 'db.php';
// Initialize the session
session_start();

$confirm_user_msg = "";

// get the task templates for the form
$taskTemplate_results = mysqli_query($link, 'SELECT * FROM taskTemplate_T WHERE templateStatus_id = 1');

//get params from form
if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $taskTemplate_id = trim($_POST["template-id"]);
    $task_deadline = trim($_POST["task-deadline"]);
    $task_created = date("Y-m-d H:i:s");

    //get the row from taskTemplate_T that needs to be copied into task_T
    $taskTemplate_row = mysqli_query($link, 'SELECT * FROM taskTemplate_T WHERE taskTemplate_id = ' . $taskTemplate_id);
    if ($taskTemplate_row->num_rows > 0) {
        while($row = $taskTemplate_row->fetch_assoc()) {
            $policy_id = $row['policy_id'];
            $task_type = $row['task_type'];
            $task_title = $row['task_title'];
            $task_instructions = $row['task_instructions'];
            $templateStatus_id = $row['templateStatus_id'];
        }
    }

    // insert a new row into Task_T
    $task_instance_sql = "INSERT INTO task_T(policy_id, task_type, task_status, task_owner, task_title, task_instructions, task_deadline, task_created) VALUES (?,?,?,?,?,?,?,?)";
    if($stmt = mysqli_prepare($link, $task_instance_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iiiissss", $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9);
        // Set parameters
        $param2 = $policy_id;
        $param3 = $task_type;
        // 2 starts for a task in progress
        $param4 = 2;
        // will come from unit A. Currently in the SESSION variable
        $param5 = $_SESSION["user_id"];
        $param6 = $task_title;
        $param7 = $task_instructions;
        $param8 = $task_deadline;
        $param9 = $task_created;
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);  
            $confirm_user_msg = "Task successfully created.";
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
    <title>Create a Task Template</title>
</head>
<body>
    <section class="hero is-info is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">
                Job Management
            </h1>
            <h2 class="subtitle">
                Create a Task
            </h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column is-2">
                    <div class="container">
                        <?php include "./vertical-menu.php"; ?>
                    </div>
                </div>
                <div class="column is-1"></div>
                <div class="column is-8">
                    <div class="box">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                        <!--Select Option-->
                        <h1 class="title">Create New Task</h1>
                        <h2 class="subtitle"><?php echo $confirm_user_msg; ?></h2>
                        <hr>
                        <label for="template-id">Select A Task Template</label>
                        <br>
                        <div class="control">
                            <div class="select">
                            <select name="template-id" id="template-id">
                                <?php while($template = mysqli_fetch_object($taskTemplate_results)) { ?>
                                    <option value="<?php echo $template->taskTemplate_id; ?>"><?php echo $template->task_title; ?></option>
                                <?php } ?>
                            </select>
                            </div>
                        </div>
                        <br>

                        <label for="task-deadline">Deadline for Task</label>
                        <br>
                        <div class="field">
                            <div class="control">
                                <input type="datetime-local" name="task-deadline" id="task-deadline">
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
            <div class="column is-1"></div>
        </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                Since a task is an instance of a task template, there is a form to select a task template to create.
                This dropdown menu shows the titles of the task templates and the value of the option tag is the taskTemplate_id;
                this was accomplished with a SQL statement and a php while loop.
                The form then asks for a deadline date using the datetime-local input in HTML.
                The created date is processed by the database, so there is no need to ask the user for an input.
                With this data, a SQL statement grabs the relevant information from the taskTemplate_T and copies that data and the user input into the Task_T.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>