<?php
// Initialize the session
session_start();
	require 'db.php';
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

<CENTER>
<h3>TEMPLATES</h3>

<?php 
if(!empty($_SESSION["username"])){
echo "<h4>Hello <font color=\"red\">";
echo htmlspecialchars($_SESSION["username"]); 
echo "</font>! The page for VIEW should be here  </h4>";
}
?>

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
		</tr>
	<?php } ?>
</tbody>
</table>      

</CENTER>

</body>
</html>