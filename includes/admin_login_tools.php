<?php

# Includes helper functions
require('helpers.php') ;

# Loads a certain URL
function load( $page, $pid)
{
    # Begin URL with protocol, domain, and current directory.
    $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

    # Remove trailing slashes then append page name to URL and the admin id.
    $url = rtrim( $url, '/\\' ) ;
    $url .= '/' . $page . '?id=' . $pid;

    # Execute redirect then quit.
    session_start();
    $_SESSION['logged_in'] = true;
    header( "Location: $url" );
    exit();
}

# Validates the login.
# Returns -1 if validate fails, and returns the primary key if it succeeds
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
?>