<?php
// Initialize the session
session_start();
require 'db.php';

// get task_id from $_GET from view-one-task.php
if( !empty($_GET["task_id"]) ) {
    $current_task_id = trim( $_GET["task_id"] );
}

// ADD data item. For the first data item, update column. Second data item, duplicate and update rows
if((isset($_GET['add_data_id']) && isset($_GET['task_id']))) {
    $current_task_id = $_GET["task_id"];
    $data_id = $_GET['add_data_id'];

    // see if this is the first data item. if it is, then updating the column is the only thing needed
    $check_first_data = mysqli_query($link, 'SELECT DISTINCT COUNT(data_id) as count FROM taskDetails_T WHERE NOT data_id = 0' . ' AND task_id = ' . $current_task_id);
    $data_count = $check_first_data->fetch_assoc();

    if ($data_count['count'] != 0) {
        // this means that there is a data item already for this task. In this case, we duplicate the rows and change the data column

        // $test_sql = 'INSERT INTO taskDetails_T (task_id, user_id, data_id, taskPart_status) SELECT DISTINCT ' . $current_task_id . ', ' . $_SESSION['user_id'] . ', ' .  $data_id . ', 0 FROM taskDetails_T WHERE task_id = ' . $current_task_id;
        // mysqli_query($link, $test_sql);

        $duplicate_rows_add_new_data_sql = 'INSERT INTO taskDetails_T (task_id, user_id, data_id, taskPart_status) SELECT DISTINCT ?, user_id, ?, 0 FROM taskDetails_T WHERE task_id = ?';
        if($stmt = mysqli_prepare($link, $duplicate_rows_add_new_data_sql) ) {
           // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iii", $par1, $par3, $par4); 

            $par1 = $current_task_id;
            $par3 = $data_id;
            $par4 = $current_task_id;

            mysqli_stmt_execute($stmt);        
            mysqli_stmt_close($stmt);
        }
    } else {
        // there are no data items yet, so this is the first one. No need to duplicate, just update data column
        $update_rows_with_data_sql = 'UPDATE taskDetails_T SET data_id = ? WHERE task_id = ?';
        if($stmt = mysqli_prepare($link, $update_rows_with_data_sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
            
            $par1 = $data_id;
            $par2 = $current_task_id; 

            mysqli_stmt_execute($stmt);        
            mysqli_stmt_close($stmt);
        }
    }

}

// REMOVE data item and REMOVE the rows associated with that data item
if((isset($_GET['delete_data_id']) && isset($_GET['task_id']))) {
    $current_task_id = $_GET["task_id"];
    $data_id = $_GET['delete_data_id'];

    $delete_rows_with_data_sql = 'DELETE FROM taskDetails_T WHERE data_id = ? AND task_id = ?';
    if($stmt = mysqli_prepare($link, $delete_rows_with_data_sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ii", $par1, $par2);
        
        $par1 = $data_id;
        $par2 = $current_task_id; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// Remove a row from taskDetails
if(isset($_GET['delete_user'])) {
    $delete_this_user = $_GET['delete_user'];
    $delete_this_data = $_GET['data_id'];

    $remove_row_from_taskDetails = 'DELETE FROM taskDetails_T WHERE task_id = ? AND user_id = ? AND data_id = ?';
    if($stmt = mysqli_prepare($link, $remove_row_from_taskDetails)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
        
        $par1 = $current_task_id;
        $par2 = $delete_this_user;
        $par3 = $delete_this_data; 

        mysqli_stmt_execute($stmt);        
        mysqli_stmt_close($stmt);
    }
}

// Add user to taskDetails_T (TWO CASES - one without data the other with data)
if(isset($_GET['task_id']) && isset($_GET['add_user_id'])) {
    $current_task_id = $_GET['task_id'];
    $add_user_id = $_GET['add_user_id'];

    // Check to see if there are any data items first
    $add_user_data_row = mysqli_query($link, 'SELECT DISTINCT COUNT(data_id) as count FROM taskDetails_T WHERE NOT data_id = 0 AND task_id = ' . $current_task_id);
    $data_count = $add_user_data_row->fetch_assoc();

    if ($data_count['count'] != 0) {
        // There is already a data item associated with this task. Use subquery to insert multiple rows
        $add_user_with_data_sql = 'INSERT INTO taskDetails_T (task_id, user_id, data_id, taskPart_status) SELECT DISTINCT ?, ?, data_id, ? FROM taskDetails_T WHERE task_id = ?';
        if($stmt = mysqli_prepare($link, $add_user_with_data_sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiii", $par1, $par2, $par3, $par4);
            
            $par1 = $current_task_id;
            $par2 = $add_user_id; 
            // the status should be hard-coded to be in-progress upon creation
            $par3 = 0;
            $par4 = $current_task_id;
    
            mysqli_stmt_execute($stmt);        
            mysqli_stmt_close($stmt);
        }
    } else {
        // There are no data items associated with this task. Insert a 0 instead
        $add_user_no_data_sql = 'INSERT INTO taskDetails_T(task_id, user_id, taskPart_status) VALUES (?,?,?)';
        if($stmt = mysqli_prepare($link, $add_user_no_data_sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iii", $par1, $par2, $par3);
            
            $par1 = $current_task_id;
            $par2 = $add_user_id; 
            $par3 = 0;

            mysqli_stmt_execute($stmt);        
            mysqli_stmt_close($stmt);
        }
    }

}


// view all taskDetails for this task
$taskDetails_results = mysqli_query($link, 'SELECT * FROM taskDetails_T WHERE task_id = ' . $current_task_id);

// view distinct data items for this task
$taskDetails_data_results = mysqli_query($link, 'SELECT DISTINCT data_id FROM taskDetails_T WHERE task_id = ' . $current_task_id);

?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <?php include "./navbar.php"; ?>
    <section class="hero is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
            <h1 class="title">Task Admin</h1>
            <h2 class="subtitle">Manage Task Details Table</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="box">
                    <!-- The first level is for the headings -->
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Users</p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Work Status</p>
                          </div>
                        </div>
                        <!-- Data ID -->
                        <div class="level-item">
                          <div>
                            <p class="heading">Data ID</p>
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
                    <?php while($taskDetails = mysqli_fetch_object($taskDetails_results) )  { ?>
                    <div class="level">
                        <!-- User ID -->
                        <div class="level-item">
                          <div>
                            <p class="content"><?php echo $taskDetails->user_id; ?></p>
                          </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                          <div>
                            <p class="content">
                                <?php if ($taskDetails->taskPart_status == 0): ?>
                                <span>In-Progress</span>
                                <?php elseif ($taskDetails->taskPart_status == 1): ?>
                                    <span>Completed</span>
                                <?php else: ?>
                                    <span>Rejected</span>
                                <?php endif; ?>
                            </p>
                          </div>
                        </div>
                        <!-- Data Item ID -->
                        <div class="level-item">
                          <div>
                            <p class="content">
                                <?php echo $taskDetails->data_id; ?>
                            </p>
                          </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                          <div>
                            <a href="./task-admin.php?task_id=<?php echo $current_task_id; ?>&delete_user=<?php echo $taskDetails->user_id; ?>&data_id=<?php echo $taskDetails->data_id; ?>">
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
                            <p class="content">Add new user to task</p>
                            </div>
                        </div>
                        <!-- Empty Level Item for spacing -->
                        <div class="level-item">
                            <div></div>
                        </div>
                    </div>
                    <!-- Hidden Form to Add a Row -->
                    <div class="notification is-hidden" id="add-user-form">
                        <form action="./task-admin.php" method="GET">

                            <!--Select Option-->
                            <p class="content">PLACEHOLDER (Unit A)</p>
                            <label for="add_user_id">Select A User</label>
                            <br>
                            <div class="control">
                                <div class="select">
                                <select name="add_user_id" id="add_user_id">
                                    <option value="001">001</option>
                                    <option value="002">002</option>
                                    <option value="003">003</option>
                                    <option value="004">004</option>
                                    <option value="005">005</option>
                                    <option value="006">006</option>
                                    <option value="007">007</option>
                                    <option value="008">008</option>
                                </select>
                                </div>
                            </div>
                            <br>
                            
                            <!-- Hidden field to perserve task_id upon submit -->
                            <input type="hidden" name="task_id" value="<?php echo $current_task_id; ?>">

                            <!-- Submit Button -->
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
                        <!-- Work Status -->
                        <div class="level-item">
                            <div>
                            <p class="heading">Work Status</p>
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
                    <?php while($taskDetails = mysqli_fetch_object($taskDetails_data_results) )  { ?>
                    <div class="level">
                        <!-- Data Item -->
                        <div class="level-item">
                            <div>
                            <p class="content"><?php echo $taskDetails->data_id; ?></p>
                            </div>
                        </div>
                        <!-- Work Status -->
                        <div class="level-item">
                            <div>
                            <button class="button is-primary">Details</button>
                            </div>
                        </div>
                        <!-- Remove Button -->
                        <div class="level-item">
                            <div>
                            <a href="./task-admin.php?task_id=<?php echo $current_task_id ?>&delete_data_id=<?php echo $taskDetails->data_id; ?>">
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
                            <p class="content">Add new data item to task</p>
                            </div>
                        </div>
                        <!-- Empty Level Item for spacing -->
                        <div class="level-item">
                            <div></div>
                        </div>
                    </div>
                    <div class="notification is-hidden" id="add-data-form">
                        <form action="./task-admin.php" method="GET">

                            <!--Select Option-->
                            <p class="content">PLACEHOLDER (Unit CD)</p>
                            <label for="add_user_id">Select A Data Item</label>
                            <br>
                            <div class="control">
                                <div class="select">
                                <select name="add_data_id" id="add_data_id">
                                    <option value="0104">0104</option>
                                    <option value="0105">0105</option>
                                </select>
                                </div>
                            </div>
                            <br>
                            
                            <!-- Hidden field to perserve task_id upon submit -->
                            <input type="hidden" name="task_id" value="<?php echo $current_task_id; ?>">

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
                                    <h2 class="title is-6">Access Contracts (for all users and data in this task)</h2>
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
                    <section>
                        <button class="button is-warning">Save</button>
                        <button class="button is-link">Submit</button>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container is-fluid has-background-dark has-text-white">
            <br>
            <p class="title has-text-white">Page Protocol</p>
            <p class="content">
                As Professor Pham explained in her Word document describing the details tables, there are several cases to consider on this page.
                The first case is to add a user with no data item. That is a simple INSERT INTO SQL statement where the data_id is set to 0.
                Since the data_id is needed as a composite primary key, it cannot be zero.
                The second case is to add a data item.
                For the first data item, all that is needed is an UPDATE SQL statement to set the data_id of all current rows to said data_id.
                The third case is to add a new user while there is a data item already.
                Using a SUBQUERY, we can insert the apporiate number of rows based on the number of distinct data items.
                The fourth case is to add two or more data items.
                In this case, we duplicate the current user rows and update the data_id with the newly added data item with a SUBQUERY.
                The forms are toggled visible or invisible using JavaScript and the Bulma is-hidden class.
                Deleting users and data items are self-explanatory.
                There are some placeholders for other units and their data; you cannot see the policies and the contract from Unit A for example.
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