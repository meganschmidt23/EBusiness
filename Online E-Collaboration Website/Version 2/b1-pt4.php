<?php
// Initialize the session
session_start();
require 'db.php';

$result = mysqli_query($link, 'select * from templates');
$result_steps = mysqli_query($link, 'select * from stepdetails');

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Get deadline
    $deadline = trim($_POST["new-deadline"]);        

    // Get job status
    $status = trim($_POST["job-status"]); 

    $sql = "UPDATE tasks SET deadline = ?, status = ? WHERE job_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $par1, $par2, $par3);
        
        // Set parameters
        $par1 = $deadline;
        $par2 = $status; 
        $par3 = 1;

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);        

        // Close statement
        mysqli_stmt_close($stmt);
    }
}



?> 

<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

<CENTER>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for EDIT should be here  </h4>";
}
?>       
<p> <br>
<p> <br>
<p> <br>
<H4> The page for EDIT should be here  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 90%">
    <tbody>
        <tr style="border-bottom: 1px solid">
            <th style="text-align: center;">TemplateID</th>
            <th style="text-align: center;">Title</th>
            <th style="text-align: center;">Template Type</th>
            <th style="text-align: center;">Owner</th>
            <th style="text-align: center;">Access Level</th>
            <th style="text-align: center;">Date Created</th>
            <th style="text-align: center;">Edit</th>
        </tr>
    
	    <?php while($templateList = mysqli_fetch_object($result)) { ?>
        <tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $templateList->template_id; ?></td>
			<td style="text-align: center;"><?php echo $templateList->title; ?></td>
			<td style="text-align: center;"><?php echo $templateList->template_type; ?></td>
			<td style="text-align: center;"><?php echo $templateList->owner; ?></td>
			<td style="text-align: center;"><?php echo $templateList->access_level; ?></td>
			<td style="text-align: center;"><?php echo $templateList->date_created; ?></td>
            <td><a href="./edit-form-template.php?template_id=<?php echo $templateList->template_id; ?>" id="edit-btn">Edit</button></a>
        </tr>
        <!-- Empty row to separate job from tasks -->
        <tr>
        <th>STEPS</th>
        </tr>

        <?php while($stepList = mysqli_fetch_object($result_steps)) { ?>
        <tr style="border-bottom: 1px solid">
			<td style="text-align: center;">Step ID: <?php echo $stepList->step_id; ?></td>
			<td style="text-align: center;">Template ID: <?php echo $stepList->template_id; ?></td>
			<td style="text-align: center;">Step Title: <?php echo $stepList->step_title; ?></td>
			<td style="text-align: center;">Order: <?php echo $stepList->order; ?></td>
        </tr>      
	    <?php } ?>
        <?php } ?>  
    </tbody>
</table>

</CENTER>

</body>
</html>