<?php

function show_quicklinks($dbc) {
  # Create a query to 
  $query = 'SELECT * FROM stuff ORDER BY update_date ASC' ;

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

      # For each row result, generate a table row with ID number
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = results.php?id=' . $row['id'] . '>' . $row['name'] . ' </A>';
         
        echo '<TR>' ;
        echo '<TD>' . $row['update_date'] . '</TD>';  
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

#pull all location names from database and generate a dropdown option for each
function dropdown_locations($dbc)
{
    # Create a query to
    $query = 'SELECT name FROM locations';

    # Execute the query
    $results = mysqli_query($dbc, $query);
    check_results($results);

    # Show results
    if ($results) {
        # But...wait until we know the query succeed before
        # For each row result, generate a dropdown option with location name
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
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