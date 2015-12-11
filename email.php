<?php
// Make sure POST variables are set for the item ID and user email before starting
session_start(); 
if (isset($_POST['id']) && isset($_POST['address'])) {
?>

<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<?php 
	// Connect and populate database
	require('includes/init.php'); 
	// Include all helper functions
	require('includes/helpers.php');
	// Include basic navbar template for top of page
	require('templates/navbar.php');
?>
<!-- Main page content: -->
<div id="mainForm">

<?php 
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		// Send the email to the user who owns the item
		$address = $_POST['address'];
		$id = $_POST['id'];
		$error = sendEmail($dbc, $address, $id);

		echo "<h1>" . $error . "</h1>";

		// ...and redirect to the home page.
		$_SESSION['emailed'] = true;
		header('Location: index.php');
    	die("Redirecting to index.php!");
	 }

?>

</div>
</body>
</html>

<?php

} else {
	# Redirect to home since there is no ID or email in:
    header('Location: index.php');
    die("Not finding or claiming an item, returning home..");
}
?>