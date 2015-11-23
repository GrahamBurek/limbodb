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
<!-- Header and description -->
    <h1>Login to Limbo</h1>
    <h3>Enter your credentials.</h3>
<form action="validate.php" method="post">
	<p> Username: <input type="text" name="username" placeholder="Username"></p>
	<p> Password: <input type="text" name="password" placeholder="Password"></p>

	<input type="button" onclick="location.href='index.php';" value="Home" style="width:75px;" />
	<button type="submit" name="submit">Submit</button>

</form>

</div>

</body>
</html>