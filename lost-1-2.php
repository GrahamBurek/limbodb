<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Limbo | Create Listing</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>

    <?php
    # Connect/populate database and include helper functions
    require('/includes/init.php');
    require('/includes/helpers.php');
    

# Set sticky variables to the empty string initially:
    $item = "";
    $type = "";
    $color = "";
    $location = "";
    $date = "";
    $descr = "";
    $email = "";
    $status = "";

    # Store GET variables, if applicable
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }

    if (isset($_GET['color'])) {
        $color = $_GET['color'];
    }

    if (isset($_GET['location'])) {
        $location = $_GET['location'];
    }

    if (isset($_GET['date'])) {
        $date = $_GET['date'];
    }

    # Store POST variables, if applicable
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $item = $_POST['listing-name'];
        $location = $_POST['location'];
        $type = $_POST['item-type'];
        $color = $_POST['item-color'];
        $descr = $_POST['further-description'];
        $date = $_POST['date'];
        $email = $_POST['email'];
        $status = $_POST['status'];
    }

    ?>

    <!-- Navbar at top of page -->
    <div id="navbar">
        <ul>
            <a href="index.php"><li>LIMBO Lost & Found
            </li></a><a href="index.php"><li>Home
        </li></a><a href="found.php"><li>Found Something?
    </li></a><a href="lost.php"><li class="current">Lost Something?</li></a>
</ul>
</div>

<!-- Main page content: -->
<div id="mainForm">

    <h1>New Listing Creation</h1>
    <h3>Create a new listing to help find your lost item.</h3>

    <p style="font-size:12px;"><span class="required">*</span> = Required</p>
    <br>

    <!-- start form -->
    <form action="lost-1-2.php" method="post" enctype="multipart/form-data" class="itemform">
        <?php

        // Listing creation and image upload logic
        if($_SERVER['REQUEST_METHOD']=="POST"){

            // Validate user input
            if(validate_listing($item, $location, $type, $color, $date, $email)){

                $filename =  $_FILES["imgfile"]["name"];
                $image = "uploads/$filename";

                // Make sure the image file type is valid
                if (($_FILES["imgfile"]["type"] == "image/gif")|| ($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg"))
                {
                    // Checks for redundant filename
                    if(file_exists($_FILES["imgfile"]["name"]))
                    {
                        echo "Image file name exists.";

                    }
                    else
                    {
                        // Uploads file and inserts listing since everything is valid
                        move_uploaded_file($_FILES["imgfile"]["tmp_name"], "uploads/$filename");
                        echo "Image upload Successful . <a href='uploads/$filename'>Click here</a> to view the uploaded image";

                        insert_item($dbc, $item, $location, $category, $color, $descr, $date, $email, $status, $image);
                        $_SESSION['inserted'] = true;
                        header('Location: index.php');
                        die("Unauthorized User!");
                    }
                }
                else if (empty($_FILES["imgfile"]["name"]))
                {
                    // Inserts the listing if valid (no image)
                    insert_item($dbc, $item, $location, $category, $color, $descr, $date, $email, $status, $image);
                    $_SESSION['inserted'] = true;
                    header('Location: index.php');
                    die("Unauthorized User!");
                }
                else {
                    echo "Invalid image file type.";
                }
                

                

            } 

        }


        ?>

        <!--text field for listing name-->
        <p><input type="text" name="listing-name" placeholder="Listing Name"><span class="required">*</span></p>

        <!--drop down with item types -->
        <p><select name="item-type">
            <option value="" disabled selected>Item Type</option>
            <option value="Electronics" <?php echo ($type == 'Electronics') ? "selected" : "";  ?> >Electronics</option>
            <option value="Clothing" <?php echo ($type == 'Clothing') ? "selected" : "";  ?> >Clothing</option>
            <option value="School Supplies" <?php echo ($type == 'School Supplies') ? "selected" : "";  ?> >School Supplies</option>
            <option value="Other" <?php echo ($type == 'Other') ? "selected" : "";  ?> >Other</option>
        </select>

        <span class="required" style="margin-right:40px;">*</span>

        <!-- text field for color-->
        <input type="text" name="item-color" placeholder="Color"  <?php echo (!empty($color)) ? 'value="' . $color . '"' : ""; ?> >
        <span class="required">*</span></p>
        <p></br>



            <!--generates drop down of locations from database-->
            <select name="location">
                <?php 
                echo "<option value='' disabled selected>Location</option>";
                dropdown_locations_selected($dbc, $location); ?>
            </select>
            <span class="required">*</span></p></p>


            <!-- date field for when item was lost -->
            <p>Date lost: <input name="date" type="date" value="<?php echo $date; ?>"><span class="required">*</span></p>

            <!-- text field for email address-->
            <p><input type="text" name="email" placeholder="E-Mail Address">

                <span class="required" style= "margin-right:45px;">*</span>

                <!-- File browser for picking an image to upload -->
                Upload an Image:<input type="file" name="imgfile"><br></p>

                <!-- Textarea for description of item -->
                <p><textarea name="further-description" placeholder="Further Description"></textarea></p>

                
                <!-- Hidden values to be used in HTTP request -->
                <input type="text" name="status" value="Lost" hidden>

                <input action="action" type="button" class="back-button" value="Back" onclick="history.go(-1);" style="width:75px;"/>

                <button type="submit" name="submit" onclick="return confirm('Are you sure all the information is correct?')">Submit</button>
            </form>
        </div>
    </body>
    </html>