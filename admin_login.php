<!DOCTYPE html>
<html>
<?php
# Connect to MySQL server and the database
require( 'includes/connect_db.php' ) ;

# Includes helper functions for login
require( 'includes/admin_login_tools.php' ) ;

if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {

    $username = $_POST['username'] ;
    $password = $_POST['password'] ;

    $pid = validate($username, $password) ;

    if($pid == -1)
        echo '<P style=color:red>Login failed, please check your credentials.</P>' ;

    else
        load('admin.php', $pid);
}
?>
<!-- Get inputs from the user. -->
<h1>Admin Login</h1>
<form action="admin_login.php" method="POST">
    <table>
        <tr>
            <td>Username:</td><td><input type="text" name="username"></td>
        </tr>
        <tr>
            <td>Password:</td><td><input type="text" name="password"></td>
        </tr>
    </table>
    <p><input type="submit" ></p>
</form>
</html>