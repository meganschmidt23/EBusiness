<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Vertical Navbar | B1</title>
    <style>
        p.menu-label { cursor: pointer; }
    </style>
</head>
<body>
    <aside class="menu">
    <p class="menu-label title is-6">
        B1 Job Management
    </p>
    <ul class="menu-list">
        <li><a href="./index.php">Sign In</a></li>
    </ul>
    <!-- Task Templates -->
    <p class="menu-label" id="task-template">
        Task Templates
    </p>
    <ul class="menu-list is-hidden" id="task-template-list">
        <li>
        <ul>
            <li><a href="./view-all-task-templates-table.php">View Task Templates Table</a></li>
            <li><a href="./view-all-task-templates.php">View Task Templates List</a></li>
            <li><a class="navbar-item" href="./create-task-template.php">Create Task Template</a></li>
        </ul>
        </li>
    </ul>
    <!-- Job Templates -->
    <p class="menu-label" id="job-template">
        Job Templates
    </p>
    <ul class="menu-list is-hidden" id="job-template-list">
        <li>
        <ul>
            <li><a href="./view-all-job-templates-table.php">View Job Templates Table</a></li>
            <li><a href="./view-all-job-templates.php">View Job Templates List</a></li>
            <li><a class="navbar-item" href="./create-job-template.php">Create Job Template</a></li>
        </ul>
        </li>
    </ul>
    <!-- Tasks -->
    <p class="menu-label" id="task">
        Tasks
    </p>
    <ul class="menu-list is-hidden" id="task-list">
        <li>
        <ul>
            <li><a href="./view-tasks-table.php">View Tasks Table</a></li>
            <li><a href="./view-all-tasks.php">View Tasks List</a></li>
            <li><a href="./create-task.php">Create Task</a></li>
        </ul>
        </li>
    </ul>
    <!-- Jobs -->
    <p class="menu-label" id="job">
        Jobs
    </p>
    <ul class="menu-list is-hidden" id="job-list">
        <li>
        <ul>
            <li><a href="./view-jobs-table.php">View Jobs Table</a></li>
            <li><a href="./view-all-jobs.php">View Jobs List</a></li>
            <li><a href="./create-job.php">Create Job</a></li>
        </ul>
        </li>
    </ul>
    </aside>

    <!-- hidden field to echo php variables to DOM to fetch with JS -->
    <div id="topic" class="is-hidden">
        <p id="page-topic"><?php echo $current_topic ?></p>
    </div>

<script>
    const taskTemplate = document.getElementById("task-template");
    const taskTemplateList = document.getElementById("task-template-list");
    taskTemplate.addEventListener("click", function() {
        taskTemplateList.classList.toggle("is-hidden");
    })
    const jobTemplate = document.getElementById("job-template");
    const jobTemplateList = document.getElementById("job-template-list");
    jobTemplate.addEventListener("click", function() {
        jobTemplateList.classList.toggle("is-hidden");
    })
    const task = document.getElementById("task");
    const taskList = document.getElementById("task-list");
    task.addEventListener("click", function() {
        taskList.classList.toggle("is-hidden");
    })
    const job = document.getElementById("job");
    const jobList = document.getElementById("job-list");
    job.addEventListener("click", function() {
        jobList.classList.toggle("is-hidden");
    })

    // click the current topic to reveal the navbar section
    const currentTopic = document.getElementById("page-topic").textContent;
    console.log(currentTopic);
    if(currentTopic == 'task') {
        task.click();
    }
    else if(currentTopic == 'job') {
        job.click();
    }
    else if(currentTopic == 'task-template') {
        taskTemplate.click();
    }
    else if(currentTopic == 'job-template') {
        jobTemplate.click();
    }
</script>

</body>
</html>