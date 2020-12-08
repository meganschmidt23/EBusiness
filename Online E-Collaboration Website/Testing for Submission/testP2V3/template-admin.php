<?php
// Initialize the session
session_start();
require 'db.php';

// get taskTemplate_id from $_GET from view-one-task.php
if( !empty($_GET["taskTemplate_id"]) ) {
    $current_taskTemplate_id = trim( $_GET["taskTemplate_id"] );
}

// NEW ADD data item. For the first data item, update column. Second data item, duplicate and update rows

if((isset($_GET['add_dataType_id']) && isset($_GET['taskTemplate_id']))) {
    $current_taskTemplate_id = $_GET["taskTemplate_id"];
    $dataType_id = $_GET['add_dataType_id'];

    // see if this is the first data item. if it is, then updating the column is the only thing needed
    $check_first_data = mysqli_query($link, 'SELECT DISTINCT COUNT(dataType_id) as count FROM taskTemplateDetails_T WHERE NOT dataType_id = 0' . ' AND taskTemplate_id = ' . $current_taskTemplate_id);
    $data_count = $check_first_data->fetch_assoc();

    if ($data_count['count'] != 0) {
    // this means that there is a data item already for this task. In this case, we duplicate the rows and change the data column


    $duplicate_rows_add_new_data_sql = 'INSERT INTO taskTemplateDetails_T (taskTemplate_id, userRole_id, dataType_id) SELECT DISTINCT ?, userRole_id, ? FROM taskTemplateDetails_T WHERE taskTemplate_id = ?';
    if($stmt = mysqli_prepare($link, $duplicate_rows_add_new_data_sql) ) {
       // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par3, $par4); 

        $par1 = $current_taskTemplate_id;
        $par3 = $dataType_id;
        $par4 = $current_taskTemplate_id;

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
} else {
    // there are no data items yet, so this is the first one. No need to duplicate, just update data column
    $update_rows_with_data_sql = 'UPDATE taskTemplateDetails_T SET dataType_id = ? WHERE taskTemplate_id = ?';
    if($stmt = mysqli_prepare($link, $update_rows_with_data_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);

        $par1 = $dataType_id;
        $par2 = $current_taskTemplate_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
        }
    }

}

// NEW REMOVE data item and REMOVE the rows associated with that data item
if((isset($_GET['delete_dataType_id']) && isset($_GET['taskTemplate_id']))) {
    $current_taskTemplate_id = $_GET["taskTemplate_id"];
    $dataType_id = $_GET['delete_dataType_id'];


    $delete_rows_with_data_sql = 'DELETE FROM taskTemplateDetails_T WHERE dataType_id = ? AND taskTemplate_id = ?';
    if($stmt = mysqli_prepare($link, $delete_rows_with_data_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
       $par1 = $dataType_id;
       $par2 = $current_taskTemplate_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// Remove a row from taskTemplateDetails
if(isset($_GET['delete_user'])) {
    $delete_this_user = $_GET['delete_user'];
    $delete_this_data = $_GET['dataType_id'];

    $remove_row_from_taskTemplateDetails = 'DELETE FROM taskTemplateDetails_T WHERE taskTemplate_id = ? AND userRole_id = ? AND dataType_id = ?';
    if($stmt = mysqli_prepare($link, $remove_row_from_taskTemplateDetails)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $current_taskTemplate_id;
        $par2 = $delete_this_user;
        $par3 = $delete_this_data; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// Add user to taskTemplateDetails_T
if(isset($_GET['taskTemplate_id']) && isset($_GET['add_user_type'])) {
    $current_taskTemplate_id = $_GET['taskTemplate_id'];
    $add_user_type = $_GET['add_user_type'];


    // Check to see if there are any data items first
    $add_user_data_row = mysqli_query($link, 'SELECT DISTINCT COUNT(dataType_id) as count FROM taskTemplateDetails_T WHERE NOT dataType_id = 0 AND taskTemplate_id = ' . $current_taskTemplate_id);
    $data_count = $add_user_data_row->fetch_assoc();


	    if ($data_count['count'] != 0) {
	        // There is already a data item associated with this task template. Use subquery to insert multiple rows
	        $add_user_with_data_sql = 'INSERT INTO taskTemplateDetails_T (taskTemplate_id, userRole_id, dataType_id) SELECT DISTINCT ?, ?, dataType_id FROM taskTemplateDetails_T WHERE taskTemplate_id = ?';
	        if($stmt = mysqli_prepare($link, $add_user_with_data_sql)) {
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);

	            $par1 = $current_taskTemplate_id;
	            $par2 = $add_user_type; 
	            // the status should be hard-coded to be in-progress upon creation
	            //$par3 = 0;
	            $par3 = $current_taskTemplate_id;

	            mysqli_stmt_execute($stmt);        
	            mysqli_stmt_close($stmt);
	        }
	    } else {
	        // There are no data items associated with this task template. Insert a 0 instead
	        $add_user_no_data_sql = 'INSERT INTO taskTemplateDetails_T(taskTemplate_id, userRole_id, dataType_id) VALUES (?,?,?)';
	        if($stmt = mysqli_prepare($link, $add_user_no_data_sql)) {
	            // Bind variables to the prepared statement as parameters
	            mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);

	            $par1 = $current_taskTemplate_id;
	            $par2 = $add_user_type; 
	            $par3 = 0;


	        mysqli_stmt_execute($stmt);        
	        mysqli_stmt_close($stmt);
	    }
	}

}

// view all taskDetails for this task template
$taskTemplateDetails_results = mysqli_query($link, 'SELECT * FROM taskTemplateDetails_T WHERE taskTemplate_id = ' . $current_taskTemplate_id);

// view distinct data items for this task template
$taskTemplateDetails_data_results = mysqli_query($link, 'SELECT DISTINCT dataType_id FROM taskTemplateDetails_T WHERE taskTemplate_id = ' . $current_taskTemplate_id);


// task template results
$userRole_T_results = mysqli_query($link, 'SELECT * FROM userRole_T');


?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <section class="hero is-warning is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">Task Template Admin</h1>
            <h2 class="subtitle">Manage Task Template Details Table</h2>
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
                <div class="box">
                    <!-- The first level is for the headings -->
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="heading">User Types</p>
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
                    <?php while($taskTemplateDetails = mysqli_fetch_object($taskTemplateDetails_results) )  { ?>
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <span class="content is-hidden"><?php echo $taskTemplateDetails->userRole_id; ?></span>
                                <?php if ($taskTemplateDetails->userRole_id == 1): ?>
                                    <span><strong> Admin</span></strong>
                                <?php elseif ($taskTemplateDetails->userRole_id == 2): ?>
                                    <span><strong> Manager</span></strong>
                                <?php elseif ($taskTemplateDetails->userRole_id == 3): ?>
                                    <span><strong> Staff</span></strong>
                                <?php elseif ($taskTemplateDetails->userRole_id == 4): ?>
                                    <span><strong> Customer</span></strong>
                                <?php elseif ($taskTemplateDetails->userRole_id == 5): ?>
                                    <span><strong> Public</span></strong>
                                <?php else: ?>
                                    <span><strong> Any</span></strong>
                                <?php endif; ?>
                          </div>
                        </div>
                        
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <a href="./template-admin.php?taskTemplate_id=<?php echo $current_taskTemplate_id; ?>&delete_user=<?php echo $taskTemplateDetails->userRole_id; ?>&dataType_id=<?php echo $taskTemplateDetails->dataType_id; ?>">
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
                                <button class="button is-primary" id="add-user-btn">+</button>
                                </a>
                            </div>
                        </div>
                        <!-- Text -->
                        <div class="level-item">
                            <div>
                            <p class="content">ADD New USER TYPE to Template</p>
                            </div>
                        </div>
                        <!-- Empty Level Item for spacing -->
                        <div class="level-item">
                            <div></div>
                        </div>
                    </div>
                    <!-- Hidden Form to Add a Row -->
                    <div class="notification is-hidden" id="add-user-form">
                        <form action="./template-admin.php" method="GET">

                            <!--Select Option-->
                            <p class="content">PLACEHOLDER (Unit A)</p>
                            <label for="add_user_type">Select A User Type</label>
                            <br>
                            <div class="control">
                                <div class="select">
                                <select name="add_user_type" id="add_user_type">
                                    <?php while($UserRoles = mysqli_fetch_object($userRole_T_results)) { ?>
                                    
                                    <option value="<?php echo $UserRoles->userRole_id; ?>" >
                                    <?php echo $UserRoles->userRole_title; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                </div>
                            </div>
                            <br>
                            
                            <!-- Hidden field to perserve taskTemplate_id upon submit -->
                            <input type="hidden" name="taskTemplate_id" value="<?php echo $current_taskTemplate_id; ?>">

                            <!-- Submit Button-->
                            <div class="field">
                                <div class="control">
                                    <button type="submit" class="button is-info">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- DATA ITEM SECTION -->
                    <hr>
                    <!-- The first level is for the headings -->
                    <div class="level">
                        <!-- Data items -->
                        <div class="level-item">
                            <div>
                            <p class="heading">Data Items</p>
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
                    <?php while($taskTemplateDetails = mysqli_fetch_object($taskTemplateDetails_data_results) )  { ?>
                    <div class="level">
                        <!-- Data Item -->
                        <div class="level-item">
                            <div>
                            <span class="content is-hidden"><?php echo $taskTemplateDetails->dataType_id; ?></span>
                                <?php if ($taskTemplateDetails->dataType_id == 1): ?>
                                    <span><strong> Any</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 2): ?>
                                    <span><strong> Self</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 3): ?>
                                    <span><strong> Other</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 4): ?>
                                    <span><strong> Form</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 5): ?>
                                    <span><strong> File</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 6): ?>
                                    <span><strong> Database</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 7): ?>
                                    <span><strong> MessageSender</span></strong>
                                <?php elseif ($taskTemplateDetails->dataType_id == 8): ?>
                                    <span><strong> Task</span></strong>
                                <?php else: ?>
                                    <span><strong> Please Assign a Data Type</span></strong>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                            <div>
                            <a href="./template-admin.php?taskTemplate_id=<?php echo $current_taskTemplate_id ?>&delete_dataType_id=<?php echo $taskTemplateDetails->dataType_id; ?>">
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
                            <button class="button is-primary" id="add-data-btn">+</button>
                            </div>
                        </div>
                        <!-- Text -->
                        <div class="level-item">
                            <div>
                            <p class="content">ADD new Data Type to Template</p>
                            </div>
                        </div>
                        <!-- Empty Level Item for spacing -->
                        <div class="level-item">
                            <div></div>
                        </div>
                    </div>
                        <div class="notification is-hidden" id="add-data-form">
                            <form action="./template-admin.php" method="GET">
                                <p class="content">PLACEHOLDER (Unit CD)</p>
                                <label for="add_dataType_id">Select A Data Type</label>
                                <br>
                                <div class="control">
                                    <div class="select">
                                    <select name="add_dataType_id" id="add_dataType_id">
                                       <!-- <option value="001">Any</option>
                                        <option value="002">Self</option>
                                        <option value="003">Other</option> -->
                                        <option value="004">Form</option>
                                        <option value="005">File</option>
                                        <!--<option value="006">Database</option>
                                        <option value="007">MessageSender</option>
                                        <option value="008">Task</option>
                                        <option value="009">Job</option>-->
                                    </select>
                                    </div>
                                </div>
                                <br>
                                
                                <!-- Hidden field to perserve task_id upon submit -->
                                <input type="hidden" name="taskTemplate_id" value="<?php echo $current_taskTemplate_id; ?>">

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
                        <div class="level">
                            <div class="level-item">
                                <div>
                                    <h2 class="title is-6">Access Policy (for all users and data in this task)</h2>
                                </div>
                            </div>
                            <div class="level-item">
                                <div>
                                    <button class="button is-success">View & Change</button>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>
                </div>
            </div>
        </div>
        <div class="column is-1"></div>
    </section>
	<footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
             This page allows the admin to add and remove user types and data types for a given task template. Programmatically, the first case is to add a user type with no data type. That is a simple INSERT INTO SQL statement where the dataType_id is set to 0. Since the dataType_id is needed as a composite primary key, it cannot be zero. The second case is to add a data type. For the first data type, all that is needed is an UPDATE SQL statement to set the dataType_id of all current rows to said dataType_id. The third case is to add a new user type while there is a data item already. Using a SUBQUERY, we can insert the apporiate number of rows based on the number of distinct data types. The fourth case is to add two or more data items. In this case, we duplicate the current user type rows and update the data_id with the newly added data item with a SUBQUERY. The forms are toggled visible or invisible using JavaScript and the Bulma is-hidden class. Deletion of a given data type results in the deletion of all of its associated user types, deletion of a user type, however, only removes its own instance of that user type and data type combination from the database. There are some placeholders for other units and their data; you cannot see the policies and the contract from Unit A for example.
            </p>
            <br>
        </div>
    </footer>
    <script>
        // first form - add user row
        addRowBtn = document.getElementById("add-user-btn");
        addRowForm = document.getElementById("add-user-form");
        addRowBtn.addEventListener("click", function() {
            addRowForm.classList.toggle("is-hidden");
        });
        // second form - add data item
        addDataBtn = document.getElementById("add-data-btn");
        addDataForm = document.getElementById("add-data-form");
        addDataBtn.addEventListener("click", function() {
            addDataForm.classList.toggle("is-hidden");
        });
    </script>
</body>
</html>
