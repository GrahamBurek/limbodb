<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
	<link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,700' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- Navbar include statement: -->
<?php 
	require('includes/connect_db.php'); 
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
		$status = $_POST['status'];
	}
	
	if(isset($_REQUEST['submit'])){
    	$filename =  $_FILES["imgfile"]["name"];
    	$image = "uploads/$filename";
    		if ((($_FILES["imgfile"]["type"] == "image/gif")|| ($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg")) && ($_FILES["imgfile"]["size"] < 200000))
    		{
        		if(file_exists($_FILES["imgfile"]["name"]))
        		{
            		echo "Image file name exists.";
        		}
        	else
        	{
            	move_uploaded_file($_FILES["imgfile"]["tmp_name"],"uploads/$filename");
            	echo "Image upload Successful . <a href='uploads/$filename'>Click here</a> to view the uploaded image";
		    }
    		}
   			 else
    		{
        		echo "invalid file.";
    		}
	}

		

	
?>
<!-- Main white form for pages: -->
<div id="mainForm">
<?php
	if($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
    	if(isset($_GET['id'])){
      		show_listing($dbc, $id);
    	}
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST'){
		insert_item($dbc, $item, $location, $category, $color, $descr, $date, $status, $image);

		echo "<b>Success!</b>";
	}
?>
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