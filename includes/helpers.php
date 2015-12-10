<?php

require("php-mailer/class.phpmailer.php");


/* @desc Builds an a button that will lead to sending an email the listing poster.
 * The button displays different text depending on whether it is a lost or found listing
 * @param $dbc the database connection object
 * @param $id id of the listing
 */
function buildEmailButton($dbc, $id){

  // Create query to find item status
  $query = 'SELECT status FROM stuff WHERE id =' . $id; 
  
  $results = mysqli_query($dbc , $query);
  check_results($results);

  if($results){
    while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
        if ($row['status'] == "Found") {
             echo '<button onClick="getEmailAddress(' . $id . ')"/>Claim item</button>';
        } else if ($row['status'] == "Lost") {
             echo '<button onClick="getEmailAddress(' . $id . ')"/>Found this item</button>';
        } 
     } 
      
  }
    mysqli_free_result($results);
}

/* @dec sends an email to the the poster of the listing in order to notify them their item has been claimed/found.
 * @param $dbc - the database variable
 * @param $address - the destination email address
 * @param $id - the id of the claimed listing
 * @return string - success or failure with error
 */
function sendEmail($dbc, $address, $id){

  $query = 'SELECT * FROM stuff WHERE id=' . $id;

  $results = mysqli_query($dbc , $query);
  check_results($results);

  if($results){
    $uploaderEmail;

    while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
        $uploaderEmail = $row['uploaderEmail'];
    

  if($row['status'] == "Found") {

      $subject = "Somebody claimed the - " . $row['item'] . " - you found!";
      $message = "A Limbo user with the email address: " . $address . " claimed the item you found! Please respond to them to return the item.\r\nThanks,\r\nLimboDB";
      $message = wordwrap($message, 70, "\r\n");

      $mail = new PHPMailer();

      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';                       // Specify main server
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'limbodb@gmail.com';                // SMTP username
      $mail->Password = 'limbodbpassword';                  // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
      $mail->Port = 465;
      //$mail->SMTPDebug = 2;

      $mail->From = 'limbodb@gmail.com';
      $mail->FromName = 'Limbo Admin';
      $mail->AddAddress($uploaderEmail);                    // Add a recipient

      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->AltBody = $message;

      if(!$mail->Send()) {
        $error = 'Mailer Error: ' . $mail->ErrorInfo;
        return $error;
      } else {
        return 'Success!';
      }



  } else if($row['status'] == "Lost"){

      $subject = "Somebody found the - " . $row['item'] . " - you lost!";
      $message = "A Limbo user with the email address: " . $address . " found the item you lost! Please respond to them to get the item.\r\nThanks,\r\nLimboDB";
      $message = wordwrap($message, 70, "\r\n");

      $mail = new PHPMailer();

      $mail->IsSMTP();                                      // Set mailer to use SMTP
      $mail->Host = 'smtp.gmail.com';                       // Specify main server
      $mail->SMTPAuth = true;                               // Enable SMTP authentication
      $mail->Username = 'limbodb@gmail.com';                // SMTP username
      $mail->Password = 'limbodbpassword';                  // SMTP password
      $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
      $mail->Port = 465;
      //$mail->SMTPDebug = 2;

      $mail->From = 'limbodb@gmail.com';
      $mail->FromName = 'Limbo Admin';
      $mail->AddAddress($uploaderEmail);                    // Add a recipient

      $mail->Subject = $subject;
      $mail->Body    = $message;
      $mail->AltBody = $message;

      if(!$mail->Send()) {
        $error = 'Mailer Error: ' . $mail->ErrorInfo;
        return $error;
      } else {
        return 'Email was successfully sent! Wait for user to contact you.';
      }
      
  }
}
}
    mysqli_free_result($results);
}

/* @ desc pulls from the database any listings from a the time period selected
 * @param $dbc - the database connection object
 * @param $time - how far back to search for listings. Chosen from a dropdown (day/week/month)
 */
function show_recent_quicklinks($dbc, $time){
  if ($time == 'day') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 DAY) ORDER BY item_date DESC';
  } else if ($time == 'week') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 WEEK) ORDER BY item_date DESC';
  } else if ($time == 'month') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 MONTH) ORDER BY item_date DESC';
}

# Execute the query
  $results = mysqli_query($dbc , $query);
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<h3> Current Listings: </h3>';
      echo '<p>See results since: <select name="recent listings" onChange="reload(this)">
                                  <option value="" disabled selected>Select One</option>
                                  <option value="day" >1 day ago</option>
                                  <option value="week" >1 week ago</option>
                                  <option value="month" >1 month ago</option>
                                  </select></p>';
      echo '<br>';
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

  }
    mysqli_free_result( $results ) ;
  }

/* @desc generates a table of "quick links" with some information about each item and a link to the full listing page
 * @param $dbc - the database connection object
 */
function show_quicklinks($dbc) {
  # Create a query to show item quicklinks
  $query = 'SELECT id, item, item_date, status FROM stuff ORDER BY item_date DESC';

  # Execute the query
  $results = mysqli_query($dbc , $query);
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
      echo '<h3> Current Listings: </h3>';
      echo '<p>See results since: <select name="recent listings" onChange="reload(this)">
                                  <option value="" disabled selected>Select One</option>
                                  <option value="day" >1 day ago</option>
                                  <option value="week" >1 week ago</option>
                                  <option value="month" >1 month ago</option>
                                  </select></p>';
      echo '<br>';
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
  }
  # Free up the results in memory
    mysqli_free_result( $results ) ;
}

/* @desc Searches the database to find items matching the criteria given by the user
 * @param $dbc - he database connection object
 * @param $type - item type
 * @param $color - item color
 * @param $location - location where item was lost
 * @param $opposite_status - the status opposite the status of the item user is searching for
 */
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


    }
  }
  # Free up the results in memory
    mysqli_free_result( $results ) ;
}

/**
 * @desc Shows a single listing from a quicklink
 * @param $dbc - the database connection object
 * @param $id - item ID
 */
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
        echo '<img src="' . $row['image'] . '" style="width:200px; height: auto; margin:50px" />';
        echo '<p>Item Name: ' . $row['item'] . '</p>';
        echo '<p>Item Category: ' . $row['category'] . '</p>';
        echo '<p>Item Color: ' . $row['color'] . '</p>';
        if($row['location_name']) 
          echo '<p>Location where ' . strtolower($row['status']) . ': ' . $row['location_name'] . '</p>';
        echo '<p>Date ' . strtolower($row['status']) . ': ' . $row['item_date'] .'</p>';
        echo '<p>Item Description: ' . $row['description'] . '</p>';
      }

  }
  # Free up the results in memory
    mysqli_free_result( $results ) ;
}


/**
 * @desc pull all location names from database and generate a dropdown option for each. Should be put inside a <select> tag
 * @param $dbc - the database connection object
 */
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


    }
    # Free up the results in memory
    mysqli_free_result($results);
}


/**
 * @desc pull all location names from database and generate a sticky dropdown option for each. Should be put inside a <select> tag
 * @param $dbc - the database connection object
 * @param $location - location of item
 */
function dropdown_locations_selected($dbc, $location)
{
    # Create a query to
    $query = 'SELECT id, location_name FROM locations';

    # Execute the query
    $results = mysqli_query($dbc, $query);
    check_results($results);

    # Show results
    if ($results) {
        # But...wait until we know the query succeed before
        # For each row result, generate a dropdown option with location name
      $i = 1;
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
          if ($row['id'] == $location) {
            echo '<option value='. $i . ' selected>' . $row['location_name'] . '</option>';
          } else {
            echo '<option value='. $i . '>' . $row['location_name'] . '</option>';
          }
            $i++;
        }

    }
    # Free up the results in memory
    mysqli_free_result($results);
}


/**
 * @desc Checks the query results as a debugging aid
 * @param $results - the result of a query
 */
function check_results($results) {
  global $dbc;

  if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
}

/**
 * @desc checks if an image file has been added to the form and uploads it to the database if it has
 */
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


/**
 * @desc Inserts a record into the stuff table
 * @param $dbc - the database connection object
 * @param $item - the name of the item
 * @param $location - location the item was lost/found as selected from the dropdown
 * @param $category - the type of item
 * @param $color - the color of the item
 * @param $descr - the description added by the listing poster
 * @param $date - the date the item was lost/found
 * @param $status - the status of the item (lost/found)
 * @param $image - an image of the item
 * @return bool|mysqli_result - the result of the query
 */
function insert_item($dbc, $item, $location, $category, $color, $descr, $date, $status, $image) {
  $query = 'INSERT INTO stuff(item, location_id, category, color, description, item_date, create_date, update_date, status, image) 
  VALUES ("' . $item . '" , ' . $location . ' , "' . $category . '" , "' . $color . '" , "' . $descr . '" , STR_TO_DATE("' . $date . '","%Y-%m-%d"), Now(), Now(),"'. $status . '", "'. $image . '" )' ;

  $results = mysqli_query($dbc, $query) ;
  check_results($results) ;

  // echo $query;
  return $results;

}
    