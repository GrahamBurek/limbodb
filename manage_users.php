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
// Connects/populates database and includes admin helper functions
require('includes/init.php');
require('includes/admin_tools.php');
?>

<!-- Navbar at top of page -->
<div id="admin-navbar">
    <ul>
        <a href="admin.php"><li>Administrator Panel</li></a>
        <a href="manage_users.php"><li class="admin-current">Manage Users</li></a>
        <a href="manage_listings.php"><li>Manage Listings</li></a>
        <a href="logout.php" style="float:right;"><li>Logout</li></a>

    </ul>
</div>

<!-- Main page content: -->
<div id="mainForm">
    <h3>Here you can manage administrators or change your password.</h3>

    <!-- Manage users table -->
    <form action="manage_users.php" method="POST">
    <?php

    // Update and show changes to admins upon POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        update_users($dbc);
        show_users($dbc);
    } else {
        show_users($dbc);
    }
    ?>
    </form>
    <button onclick= "location.href='new_admin.php'">Add New Admin</button>
    <br><br><br><br><br>

    <!-- Password change fields -->
    <form action="manage_users.php" method="POST">
        <?php
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Validates password and changes the password if valid
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

        <!-- Hidden values to be used for HTTP request -->
        <input type="hidden" name="id" value="<?php echo $pid; ?>">
        <input type="hidden" name="username" value="<?php echo($_POST['username']); ?>">
        <input type="hidden" name="password" value="<?php echo($_POST['password']); ?>">
        
       <button type="submit" name="pass-submit">Change Password</button></p>
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