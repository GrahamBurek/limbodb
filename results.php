<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
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
		$item = $_POST['listing_name']; 
		$category = $_POST['item-type'];
		$color = $_POST['color'];
		$location = $_POST['location'];
		$status = 'Found';
		
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
		insert_item($dbc, $item, $category, $color, $location, $status);
		echo "Success!";
	}
?>
<input action="action" type="button" value="Back to List" onclick="history.go(-1);" />
<input type="button" onclick="location.href='index.php';" value="Back to Home" />
<?php
	if (isset($_GET['id'])) {
		buildEmailButton($dbc, $id);
	}
	
?>
</div>
</body>
</html>