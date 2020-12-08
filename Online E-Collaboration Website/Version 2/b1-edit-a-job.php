<?php
require 'b1-editAJob.php';
if(isset($_GET['job_id']) ) {
    $job_result = mysqli_query($link, "SELECT * FROM job_T WHERE job_id = " . $_GET['job_id']);
    $job = mysqli_fetch_object($job_result);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $job_title = trim($_POST["job_title"]);

    $job_instructions= trim($_POST["job_instructions"]);

    $deadline = trim($_POST["deadline"]);   

    $sql = "UPDATE job_T SET job_title = ?, job_instructions = ?, job_deadline = ?  WHERE job_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssi", $par1, $par2, $par3, $par4);
        
        // Set parameters
        $par1 = $job_title;
        $par2 = $job_instructions; 
        $par3 = $deadline;
        $par4 = $job_id;

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);        

        // Close statement
        mysqli_stmt_close($stmt);
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a Job</title>
</head>
<body>
    <section class="section box">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="job-id" value="<?php echo $_GET['job_id'];?>">
                <div class="field">
                    <label class="label" for="job_title">Change Job Title</label>
                    <div class="control">
                        <input type="text" id = "job_title" name="job_title">
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <label class = "label" label for="job_instructions">Change Job Instructions</label>
                        <div class="control">
                            <input type="text" id="job_instructions" name="job_instructions">
                        </div>
                <div class = "field">
                    <div class = "control">
                        <label class = "label" label for ="deadline">Change Deadline </label>
                            <input type="date" id = "deadline" name="deadline">
                        </label>
                    </div>
                </div>

                <div class="control">
                    <input class="button" type="submit" value="Submit">
                </div>

        </form>
    </section>
</body>
</html>