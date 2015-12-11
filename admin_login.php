<?php
session_start();
# Check to see if user is already logged in. If so, move to admin panel.
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header('Location: admin.php');
    exit("Moved to admin panel (Already logged in).");
}
 ?>
<!DOCTYPE html>
<html>
<head>
    <title>Limbo Lost & Found</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>

<!-- Navbar at top of page: -->
<div id="navbar">
    <ul>
        <a href="index.php"><li>Limbo Lost & Found</li></a>
        <a href="index.php"><li >Home</li></a>
        <a href="found.php"><li>Found Something?</li></a>
        <a href="lost.php"><li>Lost Something?</li></a>
    </ul>
</div>
<!-- Main content of page: -->
<div id="mainForm">
    <?php
    # Connect and populate database
    require( 'includes/init.php' );

    # Includes helper functions for login
    require( 'includes/admin_tools.php' );
?>


<h1>Admin Login</h1>
<?php
    # If method is POST, try to validate the submitted credentials.
    if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {

        $username = $_POST['username'] ;
        $password = $_POST['password'] ;


        $pid = validate($dbc, $username, $password);
        
        # If validation fails, notify the user
        if($pid == -1){
            echo '<P style=color:red>Login failed, please check your credentials.</P>';
        }
        # Otherwise, log the user in and redirect to the admin panel
        else {

            $_SESSION['logged_in'] = true;
            $_SESSION['pid'] = $pid;
            load('admin.php');
        }
    }

?>
<!-- Get inputs from the user. -->
<form action="admin_login.php" method="POST">
    <table id="admin-login">
        <tr>
            <td>Username:</td><td><input type="text" name="username"></td>
        </tr>
        <tr>
            <td>Password:</td><td><input type="password" name="password"></td>
        </tr>
    </table>
    <p><input type="button" onclick="location.href='index.php';" value="Back to Limbo" /><input id="login_button" type="submit" value="Log In"></p>
</form>
    </body>
</html>