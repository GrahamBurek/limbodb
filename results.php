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
?>
<!-- Main white form for pages: -->
<div id="mainForm">
<?php
	if($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
    	if(isset($_GET['id'])){
      		show_listing($dbc, $_GET['id']);
    	}
	}
?>
<input type="button" onclick="location.href='index.php';" value="Back to Home" />
</div>
</body>
</html>