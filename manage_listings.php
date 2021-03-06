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
<!-- Navbar at top of page -->
<div id="admin-navbar">
    <ul>
        <a href="admin.php"><li>Administrator Panel</li></a>
        <a href="manage_users.php"><li>Manage Users</li></a>
        <a href="manage_listings.php"><li class="admin-current">Manage Listings</li></a>
        <a href="logout.php" style="float:right;"><li>Logout</li></a>

    </ul>
</div>

<!-- Main page content: -->
<div id="mainForm">
    <h3>Here you can change any of the item statuses. Change any statuses, then press Submit Changes.</h3>
    <form action="manage_listings.php" method="post">
        <?php

        // Update and show changes to listings on POST
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fromHere']) && $_POST['fromHere'] == 'yes'){

            echo '<p style="color:green;">Status successfully changed</p>';
            update_all_stuff_admin($dbc);
            show_all_stuff_admin($dbc);
        } else {

            show_all_stuff_admin($dbc);
        }
        ?>
        <!-- <button type="submit" name="submit">Submit Changes</button> -->
        <button type="submit" onclick="return confirm('Are you sure you wish to submit these changes?')">Submit Changes</button>

        <!-- Hidden values to be used in HTTP request -->
        <input type="hidden" name="id" value="<?php echo($pid); ?>">
        <input type="hidden" name="username" value="<?php echo($_POST['username']); ?>">
        <input type="hidden" name="password" value="<?php echo($_POST['password']); ?>">
        <input type="hidden" name="fromHere" value="yes" />
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