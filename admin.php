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
	require('includes/connect_db.php'); 
    require('includes/admin_tools.php');
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
<h3>Welcome <?php echo(getAdmin($dbc, $pid)); ?>!</h3>
<h3>Here you can change any of the item statuses. Change any statuses, then press Submit Changes.</h3>
<form action="admin.php" method="post">
<?php

// if ($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
//     load('admin_login.php', $pid);
// }

// if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {

//     $username = $_POST['username'] ;
//     $password = $_POST['password'] ;

//     $pid = validate($username, $password) ;

//     if($pid == -1)
//         load('admin_login.php', $pid);
// }

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
    <input type="hidden" name="id" value="<?php echo($pid); ?>">
    <input type="hidden" name="username" value="<?php echo($_POST['username']); ?>">
    <input type="hidden" name="password" value="<?php echo($_POST['password']); ?>">
    <input type="hidden" name="fromHere" value="yes" />
</form>
    <form action="admin.php" method="POST">
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(isset($_POST['pass']) && isset($_POST['pass-repeat']) && strcmp($_POST['pass'],$_POST['pass-repeat'])==0){
                change_password($dbc, $_POST['id'], $_POST['pass']);
                echo '<p> Password change successful! </p>';
            } else {
                echo '<p> Please make sure passwords match </p>';
            }
        }
        ?>
        <p>New Password: <input type="password" name="pass"></p>
        <p>Repeat New Password: <input type="password" name="pass-repeat"></p>
        <input type="hidden" name="id" value="<?php echo $pid; ?>">
        <input type="hidden" name="username" value="<?php echo($_POST['username']); ?>">
        <input type="hidden" name="password" value="<?php echo($_POST['password']); ?>">
       <button type="submit" name="pass-submit">Change Password</button></p>
    </form>
    <input type="button" onclick="location.href='logout.php';" value="Logout" />
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