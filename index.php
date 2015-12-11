<?php
session_start();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Limbo | Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>

	<?php
	// Connects/populates database and includes helper functions
	require('includes/init.php'); 
	require('includes/helpers.php');

	?>

	<!-- Navbar at top of page -->
	<div id="navbar">
		<ul>
			<a href="index.php"><li>LIMBO Lost & Found
			</li></a><a href="index.php"><li class="current">Home
		</li></a><a href="found.php"><li>Found Something?
	</li></a><a href="lost.php"><li>Lost Something?</li></a>
</ul>
</div>

<!-- Main page content -->
<div id="mainForm">
	<h1>Welcome to Limbo!</h1>
	<br>
	<h3>Lost something? Found something? Reunite owners with their items by posting in Limbo!</h3>
	<?php 

	// If being redirected from item insertion, notify user of success
	if (isset($_SESSION['inserted']) && $_SESSION['inserted'] == true) {
		$_SESSION['inserted'] = false;
		echo '<p style="color:green">Item successfully inserted!</p>';
	}
	// If being redirected from claiming an itme, notify user that an email has been sent
	if(isset($_SESSION['emailed']) && $_SESSION['emailed'] == true){
		$_SESSION['emailed'] = false;
		echo '<p style="color:green">Email sent!</p>';
	}
	?>
	<br>
	<div id="table">
		<?php
	# Generate table of lost and found items
		if (isset($_GET['time'])) {
			$time = $_GET['time'];
			show_recent_quicklinks($dbc,$time);
		} else {
			show_quicklinks($dbc);
		}
		?>

		<script type="text/javascript">
		// Reloads page with correct dropdown time selected
		function reload(select){
			var url = "index.php";
			url = url + "?time=" + select.value;
			window.location.assign(url);
		}
		</script>
	</div>
</div>
</body>
</html>