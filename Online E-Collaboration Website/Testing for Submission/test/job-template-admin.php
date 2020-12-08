<?php
// initialize the session
session_start();
require 'db.php';

// get jobTemplate_id from $_GET from view-one-job-template.php
if( !empty($_GET["jobTemplate_id"]) ) {
    $current_jobTemplate_id = trim( $_GET["jobTemplate_id"] );
}

// add a task template 
if( isset($_GET['jobTemplate_id']) && isset($_GET['add_task_temp_id']) ) {
    
    $current_jobTemplate_id = $_GET['jobTemplate_id'];
    $add_task_temp_id = $_GET['add_task_temp_id'];

    $add_row_to_jobTemplateDetails_T = 'INSERT INTO jobTemplateDetails_T (jobTemplate_id, taskTemplate_id, detailStatus_id) VALUES (?, ?, ?)';
    
    if($stmt = mysqli_prepare($link, $add_row_to_jobTemplateDetails_T)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $current_jobTemplate_id;
        $par2 = $add_task_temp_id;
        $par3 = 1;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// delete a task template (hides row from view)
if( isset($_GET['jobTemplate_id']) && isset($_GET['job_taskOrder']) ) {
    
    $current_jobTemplate_id = $_GET['jobTemplate_id'];
    $current_job_taskOrder = $_GET['job_taskOrder'];
    
    $hide_row_from_jobTemplateDetails_T = 'UPDATE jobTemplateDetails_T SET detailStatus_id = 0 WHERE jobTemplate_id = ? AND job_taskOrder = ?';
    
    if( $stmt = mysqli_prepare($link, $hide_row_from_jobTemplateDetails_T) ) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $current_jobTemplate_id;
        $par2 = $current_job_taskOrder;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// job template title results
$jobTemplate_T_title_results = mysqli_query($link, 'SELECT jobTemplate_id, job_title FROM jobTemplate_T WHERE jobTemplate_id = ' . $current_jobTemplate_id);

// view all details for a job template
$jobTemplateDetails_T_results = mysqli_query($link, '
SELECT
  jobTemplateDetails_T.jobTemplate_id,
  jobTemplateDetails_T.taskTemplate_id,
  jobTemplateDetails_T.job_taskOrder,
  jobTemplateDetails_T.detailStatus_id,
  jobTemplate_T.job_title,
  taskTemplate_T.task_title
FROM
  jobTemplateDetails_T
  LEFT JOIN jobTemplate_T ON jobTemplateDetails_T.jobTemplate_id = jobTemplate_T.jobTemplate_id
  LEFT JOIN taskTemplate_T ON jobTemplateDetails_T.taskTemplate_id = taskTemplate_T.taskTemplate_id
WHERE
  detailStatus_id != 0
  AND
  jobTemplateDetails_T.jobTemplate_id = ' . $current_jobTemplate_id . '
  ORDER BY job_taskOrder ASC');

// task template results
$taskTemplate_T_results = mysqli_query($link, 'SELECT * FROM taskTemplate_T');

// toggle buttons and functions
$add_user_form_visible = "";
function open_add_user_form() {}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <section class="hero is-danger is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">Job Template Admin</h1>
                <h2 class="subtitle">Manage Job Template Details</h2>
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
            <div class="column is-1"></div>
            <div class="column is-8">
                
                <div class="box">
                    
                    <p class="title has-text-centered">
                    <?php $jobTemplate_title_details = mysqli_fetch_object($jobTemplate_T_title_results);
                        echo $jobTemplate_title_details->jobTemplate_id;
                        echo "/";
                        echo $jobTemplate_title_details->job_title; 
                        ?>
                    </p>
                    <hr>

                    <!-- the first level is for the headings across -->
                    <div class="level">
                        
                        <!-- job template id/title -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Job Template ID/Title</p>
                          </div>
                        </div>
                        
                        <!-- task template id/title -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Task Template ID/Title</p>
                          </div>
                        </div>
                        
                        <!-- job task order -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Job Task Order</p>
                          </div>
                        </div>
                        
                        <!-- remove button -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Remove</p>
                          </div>
                        </div>
                    </div>
                    
                    <!-- the second level is for the table data across -->
                    <?php $number = 1; while($jobTemplateDetails = mysqli_fetch_object($jobTemplateDetails_T_results)) { ?>
                    
                    <div class="level">
                        
                        <!-- job template id/title -->
                        <div class="level-item">
                          <div>
                            <p class="content">
                                
                                <?php 
                                    echo $jobTemplateDetails->jobTemplate_id;
                                    echo "/";
                                    echo $jobTemplateDetails->job_title; 
                                ?>
                                    
                            </p>
                          </div>
                        </div>

                        <!-- task template id/title -->
                        <div class="level-item">
                          <div>
                            <p class="content">
                                
                                <?php 
                                    echo $jobTemplateDetails->taskTemplate_id;
                                    echo "/";
                                    echo $jobTemplateDetails->task_title;
                                ?>
                                    
                            </p>
                          </div>
                        </div>
                        
                        <!-- task order -->
                        <div class="level-item">
                          <div>
                            <p class="content">

                                <?php 
                                    //echo $jobTemplateDetails->job_taskOrder;
                                    //echo "/";
                                    //echo $jobTemplateDetails->detailStatus_id;

                                $order = $jobTemplateDetails->job_taskOrder;

                                if ($order > 0) {
                                    //++$number;
                                    echo $number;
                                    $number++;
                                }
                                
                                ?>

                            </p>
                          </div>
                        </div>
                        
                        <!-- remove button -->
                        <div class="level-item">
                          <div>
                            <a href="./job-template-admin.php?jobTemplate_id=<?php echo $current_jobTemplate_id ?>&job_taskOrder=<?php echo $jobTemplateDetails->job_taskOrder; ?>">
                                <button class="button is-primary">-</button>
                            </a>
                          </div>
                        </div>

                    </div>
                    <?php } ?>
                    
                    <!-- Add new row level -->
                    <div class="level">
                        <!-- Add Button -->
                        <div class="level-item">
                            <div>
                            <button id="add-user-btn" class="button is-primary">+</button>
                            </div>
                        </div>
                        <!-- Text -->
                        <div class="level-item">
                            <div>
                            <p class="content">Add new task template to task</p>
                            </div>
                        </div>
                        <!-- Empty Level Item for spacing -->
                        <div class="level-item">
                            <div></div>
                        </div>
                    </div>
                    <!-- Hidden Form to Add a Row -->
                    <div class="notification is-hidden" id="add-task-temp-form">
                        <form action="./job-template-admin.php" method="GET">

                            <!--Select Option-->
                            <p class="content">ADD A TASK TEMPLATE (job template id: passed, task temp id: user, job task order: auto incremented, details status: hardcoded as 1)</p>
                            <label for="add_user_id">Select A Task Template ID/Title</label>
                            <br>
                            
                           
                            <div class="control">

                                <div class="select">

                                <select name="add_task_temp_id"> <!-- formerly add_user_id -->
                                    <?php while($TaskTemplateTitles = mysqli_fetch_object($taskTemplate_T_results)) { ?>
                                    
                                    <option value="<?php echo $TaskTemplateTitles->taskTemplate_id; ?>" >
                                    <?php echo $TaskTemplateTitles->task_title; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>
                            <br>
                            
                            <!-- Hidden field to perserve task_id upon submit -->
                            <input type="hidden" name="jobTemplate_id" value="<?php echo $current_jobTemplate_id; ?>">

                            <!-- Submit Button -->
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-info">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    
                    <hr>
                    <section>
                        <a href="./view-one-job-template?jobTemplate_id=<?php echo $current_jobTemplate_id; ?>"><button class="button is-info">Return To Job Template Overview</button></a>
                    </section>

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
                A SQL statement fetches data from jobTemplate_T based on the jobTemplate_id passed as a query parameter. Another SQL statement fetches the related data needed to populate the page by performing joins on multiple tables in the database: jobTemplate_T, jobTemplateDetails_T, and taskTemplate_T. There are two prepared statements, one for adding task templates and one for removing them. When adding a task, the admin can choose from a list of task templates populated by the existing information in taskTemplate_T. On submit, the jobTemplate_id, taskTemplate_id, and detailStatus_id are updated in the database (detailStatus_id is hardcoded to one so it will be displayed). If the admin chooses to remove a task template, the detailStatus_id is changed to zero and the task is "deleted" (it is actually hidden from view). Finally, the job_taskOrder is preserved as new task templates are added and removed.
            </p>
            <br>
        </div>
    </footer>

    <script type="text/javascript">
        addRowBtn = document.getElementById("add-user-btn");
        addRowForm = document.getElementById("add-task-temp-form");
        addRowBtn.addEventListener("click", function() {
        // checks to see if the class is listed or not. Add/removes class
        addRowForm.classList.toggle("is-hidden");
        });
    </script>

</body>
</html>