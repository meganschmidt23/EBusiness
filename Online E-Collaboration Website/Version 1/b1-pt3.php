<?php
// Initialize the session
session_start();
require 'db.php';
require 'jobs.php';

// Delete product in cart
if (isset ( $_GET ['index'] ) ) {

    $currentTemplate = trim($_GET['index']);

    $query = "DELETE FROM templates WHERE template_id = ?";

    if($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $par1);
        $par1 = $currentTemplate;
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

$result = mysqli_query($link, 'select * from templates');
?> 

<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for DELETE should be here  </h4>";
}
?>       
<p> <br>
<p> <br>
<p> <br>
<H4> The page for DELETE should be here  </H4>
<table border="0" cellpadding="5" style="border-collapse: collapse; width: 1000px;">
  <tbody>
    <tr style="border-bottom: 1px solid">
 		<th style="text-align: center;">TemplateID</th>
		<th style="text-align: center;">Title</th>
		<th style="text-align: center;">Template Type</th>
		<th style="text-align: center;">Owner</th>
		<th style="text-align: center;">Date Created</th>
	</tr>

	<?php while($template = mysqli_fetch_object($result)) 
	{ ?>
		<tr style="border-bottom: 1px solid">
			<td style="text-align: center;"><?php echo $template->template_id; ?></td>
			<td style="text-align: center;"><?php echo $template->title; ?></td>
			<td style="text-align: center;"><?php echo $template->template_type; ?></td>
			<td style="text-align: center;"><?php echo $template->owner; ?></td>
			<td style="text-align: center;"><?php echo $template->date_created; ?></td>
            <td>
                <a href="b1-pt3.php?index=<?php echo $template->template_id; ?>" onclick="return confirm('Are you sure?')">
                    Delete
                </a>
            </td>
		</tr>
	<?php } ?>
</tbody>
</table>  

</body>
</html>