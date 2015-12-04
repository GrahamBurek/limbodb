<?php
# Check if user has logged in to admin interface before generating page:
session_start();
if($_SESSION['logged_in'] == true) {
	$pid = $_SESSION['pid'];
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Admin Logon Page</title>
		<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
		<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
	</head>
	<body>
	<!-- Navbar include statement: -->
	<?php
	require('includes/connect_db.php');
	require('includes/admin_tools.php');
	function make_new_admin($dbc)
	{


	if ($_SERVER['REQUEST_METHOD'] == 'POST') {


		if (isset($_POST['new_admin_submit']) && isset($_POST['password-repeat']) && strcmp($_POST['password'], $_POST['password-repeat']) == 0) {

			$username = $_POST['username'];
			$firstName = $_POST['first_name'];
			$lastName = $_POST['last_name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$query = 'INSERT INTO users(username, first_name, last_name, email, pass, reg_date) VALUES("' . $username . '", "'
				. $firstName . '", "' . $lastName . '", "' . $email . '", "' . $password . '", Now())';

			# Execute the query
			$results = mysqli_query($dbc, $query);
			check_results($results);

		} else {
			echo '<p> Please make sure passwords match </p>';
		}
	}
}
?>
<div id="admin-navbar">
	<ul>
		<a href="admin.php"><li>Administrator Panel</li></a>
		<a href="manage_users.php"><li>Manage Users</li></a>
		<a href="manage_listings.php"><li>Manage Listings</li></a>
		<a href="logout.php" style="float:right;"><li>Logout</li></a>

	</ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">

<form action="new_admin.php" method="POST">

	<p><input type="text" name="username" placeholder="Username" /></p>
	<p><input type="text" name="first_name" placeholder="First Name" /></p>
	<p><input type="text" name="last_name" placeholder="Last Name" /></p>
	<p><input type="text" name="email" placeholder="E-mail" /></p>
	<p><input type="password" name="password" placeholder="Password" /></p>
	<p><input type="password" name="password-repeat" placeholder="Repeat Password"/></p>
	<p><?php make_new_admin($dbc);?></p>
	<p><input type="submit" name="new_admin_submit"></input></p>

</form>
</div>

</body>
	</html>
	<?php

} else {
	# Redirect to login since user is not logged in:
	header('Location: admin_login.php');
	die("Unauthorized User!");
}
?>