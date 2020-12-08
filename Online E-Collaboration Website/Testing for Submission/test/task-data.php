<?php
// Initialize the session
session_start();
require 'db.php';

// get task_id from $_GET
if( !empty($_GET["task_id"]) ) {
    $current_task_id = trim( $_GET["task_id"] );
}

// get the task data associated with this task and user
$taskDetails_results = mysqli_query($link, 'SELECT DISTINCT data_id FROM taskDetails_T WHERE task_id = ' . $current_task_id . ' AND user_id = ' . $_SESSION['user_id']);

$data_results = mysqli_query($link, 
'SELECT data_location 
FROM data_T 
WHERE dataStatus_id = 1111 AND (dataType_id = 4 OR dataType_id = 5) AND data_id IN 
(SELECT DISTINCT data_id FROM taskDetails_T WHERE task_id =' . $current_task_id . ' AND user_id =' . $_SESSION['user_id'] . ')');

$template_choice = mysqli_query($link, 
'SELECT DISTINCT td.data_id, d.data_location
FROM taskDetails_T td, data_T d
WHERE td.data_id = d.data_id AND (d.dataType_id = 4 OR d.dataType_id = 5) AND td.task_id = ' . $current_task_id . ' AND td.user_id = ' . $_SESSION['user_id']);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Task Data</title>
</head>
<body>
    <section class="section">
        <h1 class="title is-6">Task Actions iFrame</h1>
        <h2 class="subtitle is-6">(Unit CD PLACEHOLDER)</h2>
        <div class="columns">
            <div class="column is-half">
                <p>This tasks has the following data items:</p>
                <ul>
                    <?php while($task = mysqli_fetch_object($taskDetails_results) )  { ?>
                        <li><b><?php echo $task->data_id; ?></b></li>
                    <?php } ?>  
                </ul>
                <br>
                <p>Based on the data_id, a form or file will be displayed from these locations:</p>
                <ul>
                <?php while($data = mysqli_fetch_object($data_results) )  { ?>
                        <li><b><?php echo $data->data_location; ?></b></li>
                    <?php } ?>  
                </ul>
                <br>
                <p>Below are placeholder file and form templates for data types 4 and 5:</p>
                <section class="section">
                    <?php while($template = mysqli_fetch_object($template_choice) )  { ?>
                        <?php if($template->data_id == 104): ?>
                            <!-- If the data item is a file -->
                            <div class="columns">
                                <div class="column is-one-third">
                                    <p><?php echo $template->data_location; ?></p>
                                </div>
                                <div class="column is-three-quarters is-offset-3">
                                    <div class="buttons">
                                        <button class="button is-primary" style="float:right;">Upload</button>
                                        <button class="button is-primary" style="float:right;">Download</button>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- if the data item is a form -->
                            <div class="field">
                                <label class="label">Name</label>
                                <div class="control">
                                    <input class="input" type="text" placeholder="Text input">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Username</label>
                                <div class="control">
                                    <input class="input" type="text">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input class="input" type="email">
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Subject</label>
                                <div class="control">
                                    <div class="select">
                                        <select>
                                            <option>Select dropdown</option>
                                            <option>With options</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Message</label>
                                <div class="control">
                                    <textarea class="textarea" placeholder="Textarea"></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <label class="checkbox">
                                        <input type="checkbox">
                                        I agree to the <a href="#">terms and conditions</a>
                                    </label>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <label class="radio">
                                    <input type="radio" name="question">
                                    Yes
                                    </label>
                                    <label class="radio">
                                    <input type="radio" name="question">
                                    No
                                    </label>
                                </div>
                            </div>

                            <div class="field is-grouped">
                                <div class="control">
                                    <button class="button is-link">Submit</button>
                                </div>
                                <div class="control">
                                    <button class="button is-link is-light">Save</button>
                                </div>
                            </div>

                        <?php endif ?>
                    <?php } ?> 
                </section>
            </div>
        </div>
    </section>
</body>
</html>