<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<!-- Navbar and database include statements: -->
<?php 
	require('includes/connect_db.php');
	require('includes/helpers.php');
	require('templates/navbar.php');

	$item = $_GET['listing-name'];
	$type = $_GET['item-type'];
	$color = $_GET['item-color'];
	$location = $_GET['location'];

	# debugging code for GET variables:
	# echo $item;
	# echo $type;
	# echo $color;
	# echo $location;
	
?>
<!-- Main white form for pages: -->
<div id="mainForm">
	<!-- Header and description -->
    <h1>Do These Sound Like the item you found?</h1>
    <h3>If you think the item you found matches one of these, click it to find out more about the item and confirm you found it. Otherwise, click "None of These Match" to post a new listing.</h3>
    <!-- start form -->
    <form action="found-1-2.php">
    <?php
    show_possible_matches_found($dbc, $item, $type, $color,$location);
    ?>
    </form>
</div>
</body>
</html>