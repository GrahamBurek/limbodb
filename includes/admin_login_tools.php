<?php

# Includes helper functions
require('helpers.php') ;

# Loads a certain URL
function load( $page = 'admin.php', $pid = -1 )
{
    # Begin URL with protocol, domain, and current directory.
    $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

    # Remove trailing slashes then append page name to URL and the admin id.
    $url = rtrim( $url, '/\\' ) ;
    $url .= '/' . $page . '?id=' . $pid;

    # Execute redirect then quit.
    session_start( );

    header( "Location: $url" ) ;

    exit() ;
}

# Validates the president last name.
# Returns -1 if validate fails, and returns the primary key if it succeeds
function validate($username = '', $password='')
{
    global $dbc;

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

    return intval($pid) ;
}

function update_all_stuff_admin($dbc){
  $query = 'SELECT * FROM stuff ORDER BY item_date DESC';

  # Execute the query
  $results = mysqli_query($dbc , $query);
  check_results($results);

  while ( $row = mysqli_fetch_array($results , MYSQLI_ASSOC))
  {

    for($i = 0; $i<mysqli_num_rows($results); $i++){
      $status = $_POST['item-status' . $i];
      $id = $_POST['id' . $i];
      if($status == 'Remove'){
       $updateQuery = 'DELETE FROM stuff WHERE id=\'' . $id . '\'';  
      } else {
      $updateQuery = 'UPDATE stuff SET status =\'' . $status . '\' WHERE id=\'' . $id . '\'';
      }
      //echo $updateQuery;
      $results2 = mysqli_query($dbc , $updateQuery);
      check_results($results);
    }
  }
}

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
      # For each row result, generate a table row with a link
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = results.php?id=' . $row['id'] . '>' . $row['item'] . ' </A>';
         
        echo '<input type="hidden" name="id' . $num . '" value="' . $row['id'] . '">';

        echo '<TR>';
        echo '<TD>' . $row['item_date'] . '</TD>';

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

function change_password($dbc, $admin_id, $newpass){
    $query = 'UPDATE users SET pass ="' . $newpass . '" WHERE user_id=' . $admin_id;

    # Execute the query
    $results = mysqli_query($dbc , $query) ;
    check_results($results) ;

    echo $query;
}
?>