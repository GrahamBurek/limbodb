<?php
session_start();
?>

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

	# Set initial values of variables to the empty string:
	$type = "";
	$color = "";
	$location = "";
	$date = "";

	# Set value of variables if GET variables are set:
	if(isset($_GET['item-type'])){
		$type = $_GET['item-type'];
	}

	if(isset($_GET['item-color'])){
		$color = $_GET['item-color'];
	}

	if(isset($_GET['location'])){
		$location = $_GET['location'];
	}

	if(isset($_GET['date'])){
		$date = $_GET['date'];
	}

	# If none of type, color, or location fields are set, send user back to found.php:
	if(empty($type) && empty($color) && empty($location)){
		$_SESSION['emptyFields'] = true;
		header('Location: found.php');
		exit("No fields set, redirecting to found.php...");
	}

	$opposite_status = "Lost";

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
		<a href="found.php"><li class="current">Found Something?</li></a>
		<a href="lost.php"><li>Lost Something?</li></a>
	</ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
	<!-- Header and description -->
    <h1>Do These Sound Like the item you found?</h1>
    <h3>If you think the item you found matches one of these, click it to find out more about the item and confirm you found it. Otherwise, click "None of These Match" to post a new listing.</h3>
    <!-- start form -->
    <form id="table" action="found-1-2.php" method="get">
    <?php
    show_possible_matches($dbc, $type, $color, $location, $opposite_status);
    ?>

    <!-- Send GET variables along to found-1-2.php -->
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <input type="hidden" name="color" value="<?php echo $color; ?>">
    <input type="hidden" name="location" value="<?php echo $location; ?>">
    <input type="hidden" name="date" value="<?php echo $date; ?>">

    </form>
    <input action="action" class="back-button" type="button" value="Back" onclick="history.go(-1);" style="width:75px;" />
    <button type="submit" style="margin-left:200px;" form="table">None of These Match</button>
</div>
</body>
</html>