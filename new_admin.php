<?php
# Check if user has logged in to admin interface before generating page:
session_start();
if($_SESSION['logged_in'] == true){
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
<?php
// Connect/populate database and include admin helper functions
require('includes/init.php');
require('includes/admin_tools.php');

?>

<!-- Navbar at top of page: -->
<div id="admin-navbar">
	<ul>
		<a href="admin.php"><li>Administrator Panel</li></a>
		<a href="manage_users.php"><li>Manage Users</li></a>
		<a href="manage_listings.php"><li>Manage Listings</li></a>
		<a href="logout.php" style="float:right;"><li>Logout</li></a>

	</ul>
</div>

<!-- Main page content: -->
<div id="mainForm">

<!-- User input form to create a new admin -->
<form action="new_admin.php" method="POST">

	<p><?php make_new_admin($dbc);?></p>
	<p><input type="text" name="username" placeholder="Username" /></p>
	<p><input type="text" name="first_name" placeholder="First Name" /></p>
	<p><input type="text" name="last_name" placeholder="Last Name" /></p>
	<p><input type="text" name="email" placeholder="E-mail" /></p>
	<p><input type="password" name="password" placeholder="Password" /></p>
	<p><input type="password" name="password-repeat" placeholder="Repeat Password"/></p>

	<p><input type="submit" name="new_admin_submit" /></p>

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