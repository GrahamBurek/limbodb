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
	require('includes/helpers.php');
?>
<div id="navbar">
    <ul>
        <a href="index.php"><li>Limbo Lost & Found</li></a>
        <a href="index.php"><li>Home</li></a>
        <a href="found.php"><li>Found Something?</li></a>
        <a href="lost.php"><li>Lost Something?</li></a>
    </ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
<!-- Header -->
<h1>Administrator Panel</h1>
<form action="adminPanel.php" method="post">
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fromHere']) && $_POST['fromHere'] == 'yes'){
    echo '<h3>Status successfully changed</h3>';
    update_all_stuff_admin($dbc);
    show_all_stuff_admin($dbc);
} else {

    show_all_stuff_admin($dbc);
}
?>



    <input type="button" onclick="location.href='index.php';" value="Home" style="width:75px;" />
    <button type="submit" name="submit">Submit Changes</button>
    <input type="hidden" name="fromHere" value="yes" />
</form>
</div>

</body>
</html>