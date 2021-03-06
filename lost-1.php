<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Limbo | Search Results</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>

<?php 
	// Connect/populate database and include helper functions
	require('includes/init.php');
	require('includes/helpers.php');

	# Set initial values of variables to the empty string:
	$type = "";
	$color = "";
	$location = "";
	$date = "";
	
	# Variable to hold possible link data if redirect occurs:
	$appendToLink = array();

	# Set value of variables if GET variables are set:
	if(isset($_GET['item-type'])){
		$type = $_GET['item-type'];
		$appendToLink['item-type'] = $type;
	}

	if(isset($_GET['item-color'])){
		$color = $_GET['item-color'];
		$appendToLink['item-color'] = $color;
	}

	if(isset($_GET['location'])){
		$location = $_GET['location'];
		$appendToLink['location'] = $location;
	}

	if(isset($_GET['date'])){
		$date = $_GET['date'];
		$appendToLink['date'] = $date;
	}


	# If all type, color, and location fields are not set, send user back to found.php:
	if(empty($type) || empty($color) || empty($location)){
		if(empty($appendToLink)){
			# Tell lost.php that the user is there because of empty fields:
			$_SESSION['emptyFields'] = true;
			header('Location: lost.php');
			exit("No fields set, redirecting to lost.php...");
		} else {
			# Get amount of arguments to append to link for sticky form:
			$count = count($appendToLink);
			$link = "lost.php";
			# Build link:
			for($i = 0; $i < $count; $i++){
				if($i == 0){
					$link = $link . "?item-type=" . $appendToLink['item-type'];
				} else if($i == 1) {
					$link = $link . "&item-color=" . $appendToLink['item-color']; 
				} else if($i == 2) {
					$link = $link . "&location=" . $appendToLink['location'];
				} else if($i == 3) {
					$link = $link . "&date=" . $appendToLink['date'];
				}
			}
			$_SESSION['emptyFields'] = true;
			header('Location: ' . $link);
			exit("Redirecting to lost.php...");
		}	
		
	}

	$opposite_status = "Found";

	# debugging code for GET variables:
	# echo $item;
	# echo $type;
	# echo $color;
	# echo $location;
	
?>
<!-- Navbar at top of page -->
<div id="navbar">
    <ul>
        <a href="index.php"><li>LIMBO Lost & Found
        </li></a><a href="index.php"><li>Home
        </li></a><a href="found.php"><li>Found Something?
        </li></a><a href="lost.php"><li class="current">Lost Something?</li></a>
    </ul>
</div>

<!-- Main page content: -->
<div id="mainForm">

    <h1>Is Your Item Here?</h1>
    <h3>If you think the item you lost matches one of these, click it to find out more about the item and confirm that it is yours. Otherwise, click "None of These Match" to post a new listing.</h3>
    <!-- start form -->
    <form id="table" action="lost-1-2.php" method="get">
    	
    <?php
    // Display search results on page
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