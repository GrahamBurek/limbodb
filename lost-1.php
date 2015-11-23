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
	#require('templates/navbar.php');

	$item = $_GET['listing-name'];
	$type = $_GET['item-type'];
	$color = $_GET['item-color'];
	$location = $_GET['location'];
	$opposite_status = "Found";

	# debugging code for GET variables:
	# echo $item;
	# echo $type;
	# echo $color;
	# echo $location;
	
?>
<div id="navbar">
	<ul>
		<a href="index.php"><li>Limbo Lost & Found</li></a>
		<a href="index.php"><li >Home</li></a>
		<a href="found.php"><li>Found Something?</li></a>
		<a href="lost.php"><li class="current">Lost Something?</li></a>
	</ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
	<!-- Header and description -->
    <h1>Do These Sound Like the item you lost?</h1>
    <h3>If you think the item you lost matches one of these, click it to find out more about the item and confirm that it is yours. Otherwise, click "None of These Match" to post a new listing.</h3>
    <!-- start form -->
    <form action="lost-1-2.php">
    <?php
    show_possible_matches($dbc, $item, $type, $color, $location, $opposite_status);
    ?>
    <input type= "hidden" name= "page" value="lost-1">
    </form>
    <input type="button" onclick="location.href='lost-1-2.php';" value="None of These Match" />
</div>
</body>
</html>