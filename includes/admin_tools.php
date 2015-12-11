<?php

# Includes helper functions
require('helpers.php') ;

/**
 * @desc Gets an admin name from the database
 * @param $dbc - the database connection object
 * @param $id - the id of the item in the database
 * @return the first name of the admin
 */
function getAdmin($dbc, $id){
  # Make database query to get admin name:
  $query = "SELECT first_name FROM users WHERE user_id='" . $id . "'";

  # Execute the query
    $results = mysqli_query( $dbc, $query ) ;
    check_results($results);

    $row = mysqli_fetch_array($results , MYSQLI_ASSOC);
    $first_name = $row['first_name'];
    mysqli_free_result($results);
    return $first_name;
    
}


/**
 * @desc  Loads a certain URL
 * @param $page the page name
 */
function load( $page)
{
    # Begin URL with protocol, domain, and current directory.
    $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

    # Remove trailing slashes then append page name to URL and the admin id.
    $url = rtrim( $url, '/\\' ) ;
    $url .= '/' . $page;

    # Execute redirect then quit.
    header( "Location: $url" );
    exit();
}


/**
 * @desc Validates the login attempt
 * @param $dbc - the database connection object
 * @param $username - the admin username entered
 * @param $password - the admin password entered
 * @return int -1 if validate fails, and returns the primary key if it succeeds
 */
function validate($dbc, $username, $password)
{
    //global $dbc;

    if(empty($username)||empty($password))
        return -1 ;

    # Make the query
    $query = "SELECT user_id FROM users WHERE username='" . $username . "' AND pass='" . $password . "'" ;

    # Execute the query
    $results = mysqli_query( $dbc, $query ) ;
    check_results($results);

    # If we get no rows, the login failed
    if (mysqli_num_rows( $results ) == 0 )
        return -1 ;

    # We have at least one row, so get the first one and return it
    $row = mysqli_fetch_array($results, MYSQLI_ASSOC) ;

    $pid = $row [ 'user_id' ] ;

    mysqli_free_result($results);

    return intval($pid) ;
}


/**
 * @desc Changes the status of all items in the database the status set in the admin panel
 * @param $dbc - the database connection object
 */
function update_all_stuff_admin($dbc){
  $query = 'SELECT * FROM stuff ORDER BY item_date DESC';

  # Execute the query
  $results = mysqli_query($dbc , $query);
  check_results($results);

    if($results) {
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {

            for ($i = 0; $i < mysqli_num_rows($results); $i++) {
                $status = $_POST['item-status' . $i];
                $id = $_POST['id' . $i];
                if ($status == 'Remove') {
                    $updateQuery = 'DELETE FROM stuff WHERE id=\'' . $id . '\'';
                } else {
                    $updateQuery = 'UPDATE stuff SET status =\'' . $status . '\' WHERE id=\'' . $id . '\'';
                }
                //echo $updateQuery;
                $results2 = mysqli_query($dbc, $updateQuery);
                check_results($results2);
            }
        }
    }
    mysqli_free_result($results);
}

/**
 * @desc Generates a table of all listings with a dropdown to change status
 * @param $dbc - the database connection object
 */
function show_all_stuff_admin($dbc) {
  # Create a query to show item quicklinks
  $query = 'SELECT * FROM stuff ORDER BY item_date DESC';

  # Execute the query
  $results = mysqli_query($dbc , $query) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<h3> All Listings </h3>';
      echo '<TABLE>';
      echo '<TR>';
      echo '<TH>Date</TH>';
      echo '<TH>Status</TH>';
      echo '<TH>Item</TH>';
      echo '</TR>';

      $num = 0;
      # For each row result, generate a table row with a link and a drop down list
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = results.php?id=' . $row['id'] . '>' . $row['item'] . ' </A>';
         
        echo '<input type="hidden" name="id' . $num . '" value="' . $row['id'] . '">';

        echo '<TR>';
        echo '<TD>' . $row['item_date'] . '</TD>';

        # Make sure the correct status is selected in the dropdown
        if ($row['status'] == 'Lost') {
        echo '<TD>' . '<select name="item-status' . $num . '">
                <option value="Lost" selected>Lost</option>
                <option value="Found">Found</option>
                <option value="Claimed">Claimed</option>
                <option value="Remove">Remove</option>
            </select>' . '</TD>' ;
        }
        else if ($row['status'] == 'Found') {
        echo '<TD>' . '<select name="item-status' . $num . '">
                <option value="Lost">Lost</option>
                <option value="Found" selected>Found</option>
                <option value="Claimed">Claimed</option>
                <option value="Remove">Remove</option>
            </select>' . '</TD>' ;
        }
        else if ($row['status'] == 'Claimed') {
        echo '<TD>' . '<select name="item-status' . $num . '">
                <option value="Lost">Lost</option>
                <option value="Found">Found</option>
                <option value="Claimed" selected>Claimed</option>
                <option value="Remove">Remove</option>
            </select>' . '</TD>' ; 
        }
        echo '<TD>' . $alink . '</TD>' ;
        echo '</TR>';
        $num++;
        }
      }

      # End the table
      echo '</TABLE>';

      # Free up the results in memory
      mysqli_free_result( $results );
}

/**
 * @desc Changes the password of the currently logged in admin
 * @param $dbc - the database connection object
 * @param $admin_id - the id of the logged in admin
 * @param $newpass - the new password
 */
function change_password($dbc, $admin_id, $newpass){
    # Create query to change password
    $query = 'UPDATE users SET pass ="' . $newpass . '" WHERE user_id=' . $admin_id;

    # Execute the query
    $results = mysqli_query($dbc , $query) ;
    check_results($results) ;

}

/**
 * @desc Deletes the selected admin the corresponding delete button is pressed
 * @param $dbc - the database connection object
 */
function update_users($dbc){
    # Create query to get all admins
    $query = 'SELECT * FROM users';

    # Execute query
    $results = mysqli_query($dbc, $query);
    check_results($results);

    if($results){
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            # Goes through all admins in the table...
            for ($i = 0; $i < mysqli_num_rows($results) - 1; $i++){
                $buttonPressed = $_POST['delete' . $i];
                $id = $_POST['admin_id' . $i];

                # ...And deletes the one that the user selected
                if(isset($buttonPressed)){
                    $deleteQuery = 'DELETE FROM users WHERE user_id=' . $id;
                    $results_delete = mysqli_query($dbc, $deleteQuery);
                    check_results($results_delete);
                }
            }
        }

    }
    mysqli_free_result($results);

}

/**
 * @desc Generates a table of admins with corresponding delete buttons (except for logged in admin and superadmin)
 * @param $dbc - the database connection object
 */
function show_users($dbc) {
    # Create a query to show all users
    $query = 'SELECT user_id, username, first_name, last_name, email, reg_date FROM users ORDER BY reg_date DESC';

    # Execute the query
    $results = mysqli_query($dbc , $query);
    check_results($results) ;

    # Show results
    if( $results )
    {
        # But...wait until we know the query succeed before
        # rendering the table start.
        echo '<p> Current Adminstrators: </p>';
        echo '<br>';
        echo '<TABLE class="users">';
        echo '<TR>';
        echo '<TH>Username</TH>';
        echo '<TH>Name</TH>';
        echo '<TH>E-Mail</TH>';
        echo '<TH>Register Date</TH>';
        echo '<TH>Options</TH>';
        echo '</TR>';

        # For each row result, generate a table row

        # Increment for post variable
        $num = 0;
        while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
        {

            echo '<TR>' ;
            echo '<TD>' . $row['username'] . '</TD>';
            echo '<TD>' . $row['first_name'] . ' ' . $row['last_name'] . '</TD>' ;
            echo '<TD>' . $row['email'] . '</TD>' ;
            echo '<TD>' . $row['reg_date'] . '</TD>' ;
            echo '<TD>' . '<button onclick="return confirm(\'Are you sure you wish to delete this admin?\')" type="submit" name="delete' . $num . '" ';
            if($row['user_id'] == $_SESSION['pid'] || $row['user_id'] == 1)
                echo 'disabled';
            echo '>Delete User</button>' . '</TD>' ;
            echo '</TR>';
            echo '<input type="hidden" name="admin_id' . $num . '" value="' . $row['user_id'] . '">';
            $num++;
        }

        # End the table
        echo '</TABLE>';

        # Free up the results in memory
        mysqli_free_result( $results ) ;
    }
}

/**
* @desc Creates a new admin based on user input
* @param $dbc - the database connection object
**/
function make_new_admin($dbc)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        # Validates the user input
        if (isset($_POST['new_admin_submit']) &&
            !empty($_POST['username']) &&
            !empty($_POST['first_name']) &&
            !empty($_POST['last_name']) &&
            !empty($_POST['email']) &&
            !empty($_POST['password']) &&
            !empty($_POST['password-repeat'])){

                if(strcmp($_POST['password'], $_POST['password-repeat']) == 0) {

                $username = $_POST['username'];
                $firstName = $_POST['first_name'];
                $lastName = $_POST['last_name'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                # Create query to insert new admin into database
                $query = 'INSERT INTO users(username, first_name, last_name, email, pass, reg_date) VALUES("' . $username . '", "'
                    . $firstName . '", "' . $lastName . '", "' . $email . '", "' . $password . '", Now())';

                # Execute the query
                $results = mysqli_query($dbc, $query);
                check_results($results);
                header("Location: manage_users.php");
                exit("Redirecting to user panel");

                } else {
                     echo '<p> Please make sure passwords match </p>';
                }

        } else {
            echo '<p> Please make sure all fields are filled out </p>';
        }
    }
}


?>