<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Limbo | Listing</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
	<script type="text/javascript">

	// Prompt the user and obtain his/her email address
	function getEmailAddress(id){
 		
		var emailForm = document.createElement("form");
		emailForm.setAttribute("method", "post");
    	emailForm.setAttribute("action", "email.php");

		var address = prompt("Please enter your email address:");

		var hiddenEmailField = document.createElement("input");
		hiddenEmailField.setAttribute("type", "hidden");
		hiddenEmailField.setAttribute("name", "address");
		hiddenEmailField.setAttribute("value", address);
		emailForm.appendChild(hiddenEmailField);

		var hiddenIdField = document.createElement("input");
		hiddenIdField.setAttribute("type", "hidden");
		hiddenIdField.setAttribute("name", "id");
		hiddenIdField.setAttribute("value", id);
		emailForm.appendChild(hiddenIdField);

		document.body.appendChild(emailForm);
		emailForm.submit();

	}

</script>
</head>
<body>

<?php 
	
	// Connect/populate database and include helper functions
	require('includes/init.php'); 
	require('includes/helpers.php');
	// Include basic template navbar at the top
	require('templates/navbar.php');
	
	// Store id from GET, if applicable
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

	// Store POST variables, if applicable
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$item = $_POST['listing-name'];
		$location = $_POST['location'];
		$category = $_POST['item-type'];
		$color = $_POST['item-color'];
		$descr = $_POST['further-description'];
		$date = $_POST['date'];
		$email = $_POST['email'];
		$status = $_POST['status'];
	}

		

	
?>

<!-- Main page content: -->
<div id="mainForm">
<?php
	// Upon GET, display the listing of the item with specified id
	if($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
    	if(isset($_GET['id'])) {
      		show_listing($dbc, $id);
    	}
	}
	
?>
<br><br>

<input action="action" type="button" value="Back" onclick="history.go(-1);" />

<input type="button" onclick="location.href='index.php';" value="Home" />

<?php
	// Create the "Claim Item" button to send an email
	if (isset($_GET['id'])) {
		buildEmailButton($dbc, $id);
	}
	
?>
</div>

</body>
</html>