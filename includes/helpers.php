<?php

function show_quicklinks($dbc) {
  # Create a query to show item quicklinks
  $query = 'SELECT id, item, item_date, status FROM stuff ORDER BY item_date ASC' ;

  # Execute the query
  $results = mysqli_query( $dbc , $query ) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<h3> Current Listings </h3>';
      echo '<TABLE>';
      echo '<TR>';
      echo '<TH>Date/Time</TH>';
      echo '<TH>Status</TH>';
      echo '<TH>Item</TH>';
      echo '</TR>';

      # For each row result, generate a table row with a link
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = results.php?id=' . $row['id'] . '>' . $row['item'] . ' </A>';
         
        echo '<TR>' ;
        echo '<TD>' . $row['item_date'] . '</TD>';  
        echo '<TD>' . $row['status'] . '</TD>' ;
        echo '<TD>' . $alink . '</TD>' ;
        echo '</TR>';
        
      }

      # End the table
      echo '</TABLE>';

      # Free up the results in memory
      mysqli_free_result( $results ) ;
  }
}

function show_possible_matches($dbc, $item, $type, $color, $location, $opposite_status) {
  # Create a query to get partially matching items from database:
  $query = "SELECT stuff.id, item, location_name, category, color FROM stuff INNER JOIN locations ON stuff.location_id=locations.id WHERE status = '" . $opposite_status . "' AND (item LIKE '%". $item . "%'
  OR location_name ='" . $location . "'" .
  "OR category ='" . $type . "'" .
  "OR color ='" . $color . "')";

  # Execute the query
  $results = mysqli_query( $dbc , $query );
  check_results($results);

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table
      echo '<h3> Possible Matches </h3>';
      echo '<TABLE>';
      echo '<TR>';
      echo '<TH>Name</TH>';
      echo '<TH>Category</TH>';
      echo '<TH>Location</TH>';
      echo "<TH>Color</TH>";
      echo '</TR>';

      # For each row result, generate a table row with ID number
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = ql-found.php?id=' . $row['id'] . '>' . $row['item'] . ' </A>';
        
        echo '<TR>' ;
        echo '<TD>' . $alink . '</TD>' ;
        echo '<TD>' . $row['category'] . '</TD>';  
        echo '<TD>' . $row['location_name'] . '</TD>' ;
        echo '<TD>' . $row['color'] . '</TD>' ;
        echo '</TR>';
        
      }

      # End the table
      echo '</TABLE>';

      # Free up the results in memory
      mysqli_free_result( $results ) ;
  }
}

# Shows a single listing from a quicklink
function show_listing($dbc, $id) {
  # Create a query to 
  $query = 'SELECT * FROM stuff LEFT JOIN locations ON stuff.location_id=locations.id WHERE stuff.id = ' . $id;

  # Execute the query
  $results = mysqli_query( $dbc , $query ) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<H1>Listing</H1>' ;

      # For the result, generate a table row
      if( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        echo '<p>Item Name: ' . $row['item'] . '</p>';
        echo '<p>Item Category: ' . $row['category'] . '</p>';
        echo '<p>Item Color: ' . $row['color'] . '</p>';
        if($row['location_name']) 
          echo '<p>Location where ' . strtolower($row['status']) . ': ' . $row['location_name'] . '</p>';
        echo '<p>Date ' . strtolower($row['status']) . ': ' . $row['item_date'] .'</p>';
        echo '<p>Item Description: ' . $row['description'] . '</p>';
      }

      # Free up the results in memory
      mysqli_free_result( $results ) ;
  }
}

#pull all location names from database and generate a dropdown option for each
function dropdown_locations($dbc)
{
    # Create a query to
    $query = 'SELECT location_name FROM locations';

    # Execute the query
    $results = mysqli_query($dbc, $query);
    check_results($results);

    # Show results
    if ($results) {
        # But...wait until we know the query succeed before
        # For each row result, generate a dropdown option with location name
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            echo '<option value="' . $row['location_name'] . '">' . $row['location_name'] . '</option>';
        }
            # Free up the results in memory
            mysqli_free_result($results);

    }
}

# Checks the query results as a debugging aid
function check_results($results) {
  global $dbc;

  if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
}

# auto-populate code
/*function connect_db(){

  $dbc = @mysqli_connect ( 'localhost', 'root', '', 'limbo_db' )

  OR die ( mysqli_connect_error() ) ;

  # Set encoding to match PHP script encoding.

  mysqli_set_charset( $dbc, 'utf8' ) ;

  return $dbc;
}

function init(){
    $dbc = connect_db();
}
*/