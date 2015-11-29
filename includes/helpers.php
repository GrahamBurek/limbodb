<?php

function buildEmailButton($dbc, $id){

  // Build button differently if user is looking at a lost or a found item:
  // Create query to find item status
  $query = 'SELECT status FROM stuff WHERE id =' . $id; 
  
  $results = mysqli_query($dbc , $query);
  check_results($results);

  if($results){
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
        if ($row['status'] == "Found") {
             echo '<input type="button" onclick="location.href=\'email.php\';" value="Claim item">';
        } else if ($row['status'] == "Lost") {
             echo '<input type="button" onclick="location.href=\'email.php\';" value="Found this item">';
        } 
     } 
    
  }

}



function show_quicklinks($dbc) {
  # Create a query to show item quicklinks
  $query = 'SELECT id, item, item_date, status FROM stuff ORDER BY item_date DESC';

  # Execute the query
  $results = mysqli_query($dbc , $query) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<h3> Current Listings </h3>';
      echo '<TABLE class="stuff">';
      echo '<TR>';
      echo '<TH>Date</TH>';
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

# Searches the database to find items matching the criteria given by the user
function show_possible_matches($dbc, $type, $color, $location, $opposite_status) {
  # Create a query to get partially matching items from database:
  $query = "SELECT stuff.id, item, location_name, category, color, item_date FROM stuff INNER JOIN locations ON stuff.location_id=locations.id WHERE status = '" . $opposite_status . "' AND (location_id ='" . $location . "'" .
  "OR category ='" . $type . "'" .
  "OR color ='" . $color . "')";

  # Execute the query
  $results = mysqli_query( $dbc , $query );
  check_results($results);

  # Show results
  if( $results ) {
    if (mysqli_num_rows( $results ) == 0 ) {
        echo "<p style=color:red>No matches found in our database. Press 'None of these match' to continue.</p>";
    } else {
        echo '<h3> Possible Matches </h3>';
        echo '<TABLE>';
        echo '<TR>';
        echo '<TH>Item Name</TH>';
        echo '<TH>Item Category</TH>';
        echo '<TH>Location</TH>';
        echo "<TH>Date</TH>";
        echo '</TR>';

        # For each row result, generate a table row with ID number
        while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
        {
          $alink = '<A HREF = "results.php?id=' . $row['id'] . '">' . $row['item'] . ' </A>';
          
          echo '<TR>' ;
          echo '<TD>' . $alink . '</TD>' ;
          echo '<TD>' . $row['category'] . '</TD>';  
          echo '<TD>' . $row['location_name'] . '</TD>' ;
          echo '<TD>' . $row['item_date'] . '</TD>' ;
          echo '</TR>';
          
        }

        # End the table
        echo '</TABLE>';

        # Free up the results in memory
        mysqli_free_result( $results ) ;
    }
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
        echo '<img src="' . $row['image'] . '" />';
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
#should be put inside a <select> tag
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
      $i = 1;
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            echo '<option value='. $i . '>' . $row['location_name'] . '</option>';
            $i++;
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

function image_upload(){

if(isset($_REQUEST['submit']))
{
    $filename =  $_FILES["imgfile"]["name"];
    global $image;
    $image = "uploads/$filename";
    if ((($_FILES["imgfile"]["type"] == "image/gif")|| ($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg")) && ($_FILES["imgfile"]["size"] < 200000))
    {
        if(file_exists($_FILES["imgfile"]["name"]))
        {
            echo "Image file name exists.";
        }
        else
        {
            move_uploaded_file($_FILES["imgfile"]["tmp_name"],"uploads/$filename");
            echo "Image upload Successful . <a href='uploads/$filename'>Click here</a> to view the uploaded image";

            
        }
    }
    else
    {
        echo "invalid file.";
    }
}
else
{

    echo '
        Upload an Image:<input type="file" name="imgfile"><br>
    ';

}

}

# Inserts a record into the stuff table
function insert_item($dbc, $item, $location, $category, $color, $descr, $date, $status, $image) {
  $query = 'INSERT INTO stuff(item, location_id, category, color, description, item_date, create_date, update_date, status, image) 
  VALUES ("' . $item . '" , ' . $location . ' , "' . $category . '" , "' . $color . '" , "' . $descr . '" , STR_TO_DATE("' . $date . '","%Y-%m-%d"), Now(), Now(),"'. $status . '", "'. $image . '" )' ;

  $results = mysqli_query($dbc, $query) ;
  check_results($results) ;

  echo $query;
  return $results;

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
