<?php
// Initialize the session
session_start();
require 'db.php';

$number = 0;

// get task_id from $_GET from view-one-task.php
if( !empty($_GET["job_id"]) ) {
    $current_job_id = trim( $_GET["job_id"] );
}

if(isset($_GET['job_id']) && isset($_GET['task_id2']) && isset($_GET['taskOrderNum'])) {
    $delete_this_jobstask = $_GET['job_id'];
    $delete_this_task = $_GET['task_id2'];
    $deleted_task_order = $_GET['taskOrderNum'];

    $remove_row_from_jobDetails = 'DELETE FROM jobDetails_T WHERE job_id = ? AND task_id = ?';
    if($stmt = mysqli_prepare($link, $remove_row_from_jobDetails)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $delete_this_jobstask;
        $par2 = $delete_this_task; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }

    $update_tasks = mysqli_query($link, 'SELECT *  FROM jobDetails_T WHERE job_id ='. $delete_this_jobstask .' ORDER BY 3');
    $count = 1;
    while($taskU = mysqli_fetch_object($update_tasks)){
        $update_row = 'UPDATE jobDetails_T SET job_taskOrder = ? WHERE job_id = ? AND task_id = ?';
        if($stmt = mysqli_prepare($link, $update_row)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
            //Update Order
            if($count < $deleted_task_order){
              $par1 = $taskU->job_taskOrder; 
            }
            else{
              $par1 = $taskU->job_taskOrder - 1;
            }
            $par2 = $taskU->job_id;
            $par3 = $taskU->task_id; 
            $count ++;

            mysqli_stmt_execute($stmt);        
            mysqli_stmt_close($stmt);
        }
    }

    
}
// Add into table
if(isset($_GET['job_id']) && isset($_GET['task_id']) && isset($_GET['order_num'])) {

    $add_this_jobstask = $_GET['job_id'];
    $add_this_task = $_GET['task_id'];
    $new_order = $_GET['order_num']+1;

    $add_row_to_jobDetails = 'INSERT INTO jobDetails_T VALUES (?, ?, ?)';
    if($stmt = mysqli_prepare($link, $add_row_to_jobDetails)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $add_this_jobstask;
        $par2 = $add_this_task;
        $par3 = $new_order; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// view all taskDetails for this task
$task_results = mysqli_query($link, 'SELECT jobDetails_T.task_id AS task_id, task_T.task_title AS task_title, jobDetails_T.job_taskOrder AS job_taskOrder FROM jobDetails_T INNER JOIN task_T ON jobDetails_T.task_id = task_T.task_id WHERE job_id = '. $current_job_id);

//view tasks that are not assigned to a job atm
$task_unassign = mysqli_query($link, 'SELECT task_T.task_id AS task_id, task_T.task_title AS task_title, task_T.task_instructions AS task_instructions FROM task_T LEFT JOIN jobDetails_T ON jobDetails_T.task_id = task_T.task_id WHERE jobDetails_T.job_id IS NULL ORDER BY task_id;');

?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Job Admin</title>
</head>
<body>
    <?php include "./navbar.php"; ?>
    <section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">Job Admin</h1>
            <h2 class="subtitle">Manage Jobs</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box">
                  <div class = "level">
                  <div class="level-item">
                          <div>
                            <h1>Tasks Assigned to Job 
                              <?php echo $current_job_id?>
                            </h1>    
                          </div>
                        </div>
                  </div>
                    <!-- The first level is for the headings -->
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Task ID</p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Task Title</p>
                          </div>
                        </div>
                        <!-- Data ID -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Task Order</p>
                          </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Remove</p>
                          </div>
                        </div>
                    </div>
                    <!-- This is where the php while loop will go -->
                    <?php while($taskDetails = mysqli_fetch_object($task_results) )  { ?>
                    <? $number++ ?>
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="content"><?php echo $taskDetails->task_id; ?></p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                          <p class="content"><?php echo $taskDetails->task_title; ?></p>
                          </div>
                        </div>
                        <!-- Data Item ID -->
                        <div class="level-item">
                          <div>
                            <p class="content">
                            <p class="content"><?php echo $taskDetails->job_taskOrder; ?></p>
                          </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <a href="./job-admin.php?job_id=<?php echo $current_job_id; ?>&task_id2=<?php echo $taskDetails->task_id; ?>&taskOrderNum=<?php echo $taskDetails->job_taskOrder; ?>">
                                <button class="button is-primary">-</button>
                            </a>
                          </div>
                        </div>
                    </div>
                    <?php } ?>
                    <hr>
                    <div class = "level">
                  <div class="level-item">
                          <div>
                            <h1> Add Tasks to Job 
                              <?php echo $current_job_id?>
                            </h1>    
                          </div>
                        </div>
                  </div>
                    <!-- Add new row level -->
                    <div class="level">
                    <div class="level-item">
                          <div>
                            <p class="heading">Task ID</p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Task Title</p>
                          </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Add to Job</p>
                          </div>
                        </div>
                    </div>
                        <!-- Add Button -->
                        <?php while($tasks_unassigned = mysqli_fetch_object($task_unassign) )  { ?>
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="content"><?php echo $tasks_unassigned->task_id; ?></p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                          <p class="content"><?php echo $tasks_unassigned->task_title; ?></p>
                          </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <a href="./job-admin.php?job_id=<?php echo $current_job_id; ?>&task_id=<?php echo $tasks_unassigned->task_id; ?>&order_num=<?php echo $number?>">
                                <button class="button is-primary">+</button>
                            </a>
                          </div>
                        </div>
                    </div>
                    <?php } ?>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                This page uses a SQL query to populate the area that shows all the tasks assigned to this job. The tables that are used are job_T,
                task_T, and jobDetails_T. Our Admin has the ability to remove and add tasks to this job, so another SQL query is used to access
                all tasks that are not assigned to any job.
                On this page, a while loop was used to update the orders of the tasks as well.
            </p>
            <br>
        </div>
    </footer>
</body>
</html>