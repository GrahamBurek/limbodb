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
<!-- Navbar include statement: -->
<?php 
	require('includes/init.php'); 
	require('includes/helpers.php');
	require('templates/navbar.php');
	
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}
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
	
	// if(isset($_REQUEST['submit'])){
 //    	$filename =  $_FILES["imgfile"]["name"];
 //    	$image = "uploads/$filename";

 //    		if ((($_FILES["imgfile"]["type"] == "image/gif")|| ($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg")) && ($_FILES["imgfile"]["size"] < 2000000))
 //    		{
 //        		if(file_exists($_FILES["imgfile"]["name"]))
 //        		{
 //            		echo "Image file name exists.";
 //        		}
 //        	else
 //        	{
 //        		echo "blah";
 //            	move_uploaded_file($_FILES["imgfile"]["tmp_name"], _DIR_ . "/uploads/$filename");
 //            	echo "Image upload Successful . <a href='uploads/$filename'>Click here</a> to view the uploaded image";
	// 	    }
 //    		}
 //   			 else
 //    		{
 //        		echo "invalid file.";
 //    		}
	// }

		

	
?>
<!-- Main white form for pages: -->
<div id="mainForm">
<?php
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
	if (isset($_GET['id'])) {
		buildEmailButton($dbc, $id);
	}
	
?>
</div>

</body>
</html>