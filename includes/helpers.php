<?php

// Include third-party PHP email library
require("php-mailer/class.phpmailer.php");


/* @desc Builds an a button that will lead to sending an email the listing poster.
 * The button displays different text depending on whether it is a lost or found listing
 * @param $dbc the database connection object
 * @param $id id of the listing
 */
function buildEmailButton($dbc, $id){

  // Create query to find item status
  $query = 'SELECT status FROM stuff WHERE id =' . $id; 
  
  // Execute query
  $results = mysqli_query($dbc , $query);
  check_results($results);

  // Create different button depending on lost or found
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

/* @dec Sends an email to the the poster of the listing in order to notify them their item has been claimed/found.
 * @param $dbc - the database connection object
 * @param $address - the destination email address
 * @param $id - the id of the claimed listing
 * @return string - success or failure with error
 */
function sendEmail($dbc, $address, $id){

  ini_set("smtp_port", 465);
  ini_set("SMTP", "smtp.gmail.com");

  // Create query to get all listings
  $query = 'SELECT * FROM stuff WHERE id=' . $id;

  // Execute query
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
# Free up the results in memory
mysqli_free_result($results);
}

/* @ desc Pulls from the database any listings from a the time period selected
 * @param $dbc - the database connection object
 * @param $time - how far back to search for listings. Chosen from a dropdown (day/week/month)
 */
function show_recent_quicklinks($dbc, $time){

  // Creates a query with specified time interval to get listings
  if ($time == 'day') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 DAY) ORDER BY item_date DESC';
  } else if ($time == 'week') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 WEEK) ORDER BY item_date DESC';
  } else if ($time == 'month') {
    $query = 'SELECT id, item, item_date, status FROM stuff WHERE item_date >= DATE_SUB(Now(), INTERVAL 1 MONTH) ORDER BY item_date DESC';
  }

  # Execute the query
  $results = mysqli_query($dbc , $query);
  check_results($results);

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

/* @desc Generates a table of "quick links" with some information about each item and a link to the full listing page
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
 * @param $dbc - the database connection object
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

        # For each row result, generate a table row with partial information
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

mysqli_free_result( $results ) ;
}

/**
 * @desc Shows a single listing from a quicklink
 * @param $dbc - the database connection object
 * @param $id - item ID
 */
function show_listing($dbc, $id) {
  # Create a query to find a single listing
  $query = 'SELECT * FROM stuff LEFT JOIN locations ON stuff.location_id=locations.id WHERE stuff.id = ' . $id;

  # Execute the query
  $results = mysqli_query( $dbc , $query ) ;
  check_results($results) ;

  # Show results
  if( $results )
  {
      # But...wait until we know the query succeed before
      # rendering the table start.
    

      # For the result, generate a table row
    if( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
    {
      # Display necessary information and don't show empty information
      echo '<H1>'. $row['status'] . '</H1>' ;
      if(!($row['image'] == 'uploads/'))
        echo '<img src="' . $row['image'] . '" style="width:200px; height: auto; margin:50px" />';

      echo '<p><b>Item Name:</b> ' . $row['item'] . '</p>';
      echo '<p><b>Item Category:</b> ' . $row['category'] . '</p>';
      echo '<p><b>Item Color:</b> ' . $row['color'] . '</p>';
      echo '<p><b>Location where ' . strtolower($row['status']) . ':</b> ' . $row['location_name'] . '</p>';
      echo '<p><b>Date ' . strtolower($row['status']) . ':</b> ' . $row['item_date'] .'</p>';
      if(trim($row['description']))
      echo '<p><b>Item Description:</b> ' . $row['description'] . '</p>';
    }

  }
  # Free up the results in memory
  mysqli_free_result( $results ) ;
}


/**
 * @desc pPull all location names from database and generate a dropdown option for each. Should be put inside a <select> tag
 * @param $dbc - the database connection object
 */
function dropdown_locations($dbc)
{
    # Create a query to grab all locations from the database
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
 * @desc Pulls all location names from database and generate a dropdown option for each. Should be put inside a <select> tag. Applies sticky fields to form.
 * @param $dbc - the database connection object
 */
function dropdown_locations_sticky($dbc)
{
    # Create a query grab all locations from the database
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
      if($_GET['location'] == $i){
        echo '<option value='. $i . ' selected>' . $row['location_name'] . '</option>';
        $i++;
      } else {
        echo '<option value='. $i . '>' . $row['location_name'] . '</option>';
        $i++;
      }
    }
    # Free up the results in memory
    mysqli_free_result($results);

  }
}


/**
 * @desc Pull all location names from database and generate a sticky dropdown option for each. Should be put inside a <select> tag
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
 * @desc Checks the query results to make sure it succeeds
 * @param $results - the result of a query
 */
function check_results($results) {
  global $dbc;

  if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
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
 * @param $status - the file path to an image of the item
 * @return bool|mysqli_result - the result of the query
 */
function insert_item($dbc, $item, $location, $category, $color, $descr, $date, $email, $status, $image) {
  $query = 'INSERT INTO stuff(item, location_id, category, color, description, item_date, create_date, update_date, uploaderEmail, status, image) 
  VALUES ("' . $item . '" , ' . $location . ' , "' . $category . '" , "' . $color . '" , "' . $descr . '" , STR_TO_DATE("' . $date . '","%Y-%m-%d"), Now(), Now(),"'. $email . '", "' . $status . '", "'. $image . '" )' ;

  $results = mysqli_query($dbc, $query) ;
  check_results($results) ;

  // echo $query;
  return $results;

}


/**
 * @desc Validates user input when submitting a listing
 * @param $item - the inputted item name
 * @param $location - the selected location
 * @param $category - the selected item type
 * @param $color - the inputted item color
 * @param $date - the selected date
 * @param $email - the inputted email address
 * @param bool - true for if validation succeeds, false if it fails
 */
function validate_listing($item, $location, $category, $color, $date, $email){
  // Makes sure all required fields are completed
  if(empty($item) || empty($location) || empty($category) || empty($color) || empty($date) || empty($email)){
    echo "<p style='color:red;'>Please fill out all required fields.</p>";
    return false;
  } 
  // Makes sure that the date entered is not in the future
  else if(strtotime($date) > time()) {
    echo "<p style='color:red;'>Please enter a past date.</p>";
    return false;
  }
  // Makes sure the submitted email is a valid email address
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    echo "<p style='color:red;'>Invalid email.</p>";
    return false;
  }  else {
    return true;
  }

}
