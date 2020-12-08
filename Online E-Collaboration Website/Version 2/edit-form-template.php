<?php
session_start();
require 'db.php';
if( isset($_GET['template_id']) ) {
    $template_result = mysqli_query($link, "SELECT * FROM templates WHERE template_id = " . $_GET['template_id']);
    $step_result = mysqli_query($link, "SELECT * FROM stepdetails WHERE template_id = " . $_GET['template_id']);
    $template = mysqli_fetch_object($template_result);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get deadline
    $title = trim($_POST["new-title"]);        

    // Get job status
    $order = trim($_POST["new-order"]); 

    // Get job id to edit
    $template_id_to_edit = trim($_POST["template-id"]); 

    $sql = "UPDATE stepdetails SET step_title = ?, `order` = ? WHERE template_id = ?";
         
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sii", $par1, $par2, $par3);
        
        // Set parameters
        $par1 = $title;
        $par2 = $order; 
        $par3 = $template_id_to_edit;
        echo $par3;

        // Attempt to execute the prepared statement
        mysqli_stmt_execute($stmt);        

        // Close statement
        mysqli_stmt_close($stmt);
        header("location: b1-pt4.php");
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <section class="section box">
        <h1 class="title"><?php echo $template->title; ?></h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <?php while($stepList = mysqli_fetch_object($step_result)) { ?>

                <h2 class="title is-4">Step Title: <?php echo $stepList->step_title; ?></h2>

                <input type="hidden" name="template-id" value="<?php echo $_GET['template_id'];?>">

                <div class="field">
                    <label class="label" for="new-title">Change Title</label>
                    <div class="control">
                        <input class="input" name="new-title" type="text">
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="new-order">Change Order</label>
                    <div class="control">
                        <input class="input" name="new-order" type="number">
                    </div>
                </div>

                <div class="control">
                    <input class="button" type="submit" value="Submit">
                </div>

            <? } ?>

        </form>
    </section>
</body>
</html>