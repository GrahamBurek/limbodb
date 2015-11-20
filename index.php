<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<!-- Require statements: -->
<?php
	require('includes/connect_db.php'); 
	require('includes/helpers.php');
	require('templates/navbar.php');


?>

<div id="mainForm">
	<h1>Welcome to Limbo!</h1>
	<p>Lost something? Found something? Reunite owners with their items by posting in Limbo!</p>

<?php
	show_quicklinks($dbc);
?>


</div>
</body>
</html>