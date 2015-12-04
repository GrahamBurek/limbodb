<?php 
if (isset($_POST['id']) && isset($_POST['address'])) {
?>

<!DOCTYPE html>
<html>
<head>
	<title>Limbo Lost & Found</title>
	<!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
	<link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<!-- Navbar include statement: -->
<?php 
	require('includes/init.php'); 
	require('includes/helpers.php');
	require('templates/navbar.php');
?>
<!-- Main white form for pages: -->
<div id="mainForm">

<?php 
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$address = $_POST['address'];
		$id = $_POST['id'];
		$error = sendEmail($dbc, $address, $id);

		echo "<h1>" . $error . "</h1>";

		// if($result){
		// 	echo "<h1>Email was sent successfully. Wait for other user to contact you.</h1>";
		// } else {
		// 	echo "<h1>Email was not sent successfully. Try going back and entering your address again. If that doesn't work, contact a webmaster.</h1>";
		// }
	 }

?>

</div>
</body>
</html>

<?php

} else {
	# Redirect to home since user is not logged in:
    header('Location: index.php');
    die("Not finding or claiming an item, returning home..");
}
?>