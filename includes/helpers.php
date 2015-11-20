<?php

function show_quicklinks($dbc) {
  # Create a query to 
  $query = 'SELECT id, number, lname FROM stuff ORDER BY id ASC' ;

  # Execute the query
  $results = mysqli_query( $dbc , $query ) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<H1>Presidents</H1>' ;
      echo '<TABLE>';
      echo '<TR>';
      echo '<TH>ID</TH>';
      echo '<TH>Last Name</TH>';
      echo '</TR>';

      # For each row result, generate a table row with ID number
      while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
      {
        $alink = '<A HREF = results.php?id=' . $row['id'] . '>' . $row['id'] . '. </A>';
         
        echo '<TR>' ;
        echo '<TD ALIGN=right>' . $alink . '</TD>';  
        #echo '<TD>' . $row['id'] . '</TD>' ;
        echo '<TD>' . $row['lname'] . '</TD>' ;
        echo '</TR>';
        
      }

      # End the table
      echo '</TABLE>';

      # Free up the results in memory
      mysqli_free_result( $results ) ;
  }
}

# Checks the query results as a debugging aid
function check_results($results) {
  global $dbc;

  if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
}