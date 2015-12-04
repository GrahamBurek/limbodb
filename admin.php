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
<!-- Navbar include statement: -->
<?php 
	require('includes/init.php'); 
    require('includes/admin_tools.php');
?>
<div id="admin-navbar">
    <ul>
        <a href="admin.php" ><li class="admin-current">Administrator Panel</li></a>
        <a href="manage_users.php"><li>Manage Users</li></a>
        <a href="manage_listings.php"><li>Manage Listings</li></a>
        <a href="logout.php" style="float:right;"><li>Logout</li></a>

    </ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
<!-- Header -->
<h1>Administrator Panel</h1>
<h3>Welcome <?php echo(getAdmin($dbc, $pid)); ?>!</h3>
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