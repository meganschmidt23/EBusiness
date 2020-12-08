<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <!-- Task Templates -->
            <div class="navbar-item has-dropdown is-hoverable">

                <a class="navbar-link">Task Templates</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="./view-all-task-templates-table.php">View Task Templates Table</a>
                    <a class="navbar-item" href="./view-all-task-templates.php">View Task Templates List</a>
                    <a class="navbar-item" href="./create-task-template.php">Create Task Template</a>
                </div>

            </div>
            <!-- Job Templates -->
            <div class="navbar-item has-dropdown is-hoverable">

                <a class="navbar-link">Job Templates</a>

                <div class="navbar-dropdown">
                <a class="navbar-item" href="">View Job Templates Table</a>
                <a class="navbar-item" href="./view-all-job-templates.php">View Job Templates List</a>
                <a class="navbar-item" href="./create-job-template.php">Create Job Template</a>
                </div>

            </div>
            <!-- Tasks -->
            <div class="navbar-item has-dropdown is-hoverable">

                <a class="navbar-link">Tasks</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="./view-tasks-table.php">View Tasks Table</a>
                    <a class="navbar-item" href="./view-all-tasks.php">View Tasks List</a>
                    <a class="navbar-item" href="./create-task.php">Create Task</a>
                </div>

            </div>
            <!-- Jobs -->
            <div class="navbar-item has-dropdown is-hoverable">

                <a class="navbar-link">Jobs</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="">View Jobs Table</a>
                    <a class="navbar-item" href="./view-all-jobs.php">View Jobs List</a>
                    <a class="navbar-item" href="./create-job.php">Create Job</a>
                </div>

            </div>

        </div>

        <div class="navbar-end">
        <div class="navbar-item">
            <div class="buttons">
                <a class="button is-light" href="./index.php">Log in</a>
            </div>
        </div>
        </div>
    </div>
    </nav>
</body>
</html>
