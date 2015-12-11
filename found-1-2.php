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
    <!-- Navbar and database include statements: -->
    <?php
    require('includes/helpers.php');
    require('includes/init.php');

# Set sticky variables to the empty string initially:
    $item = "";
    $type = "";
    $color = "";
    $location = "";
    $date = "";
    $descr = "";
    $email = "";
    $status = "";

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
    <div id="navbar">
        <ul>
            <a href="index.php"><li>LIMBO Lost & Found
            </li></a><a href="index.php"><li>Home
        </li></a><a href="found.php"><li class="current">Found Something?
    </li></a><a href="lost.php"><li>Lost Something?</li></a>
</ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
    <!-- Header and description -->
    <h1>New Listing Creation</h1>
    <h3>Create a new listing to help find the item's owner.</h3>

    <p style="font-size:12px;"><span class="required">*</span> = Required</p>
    <br>

    <!-- start form -->
    <form action="found-1-2.php" method="post" enctype="multipart/form-data" class="itemform">
        <?php

        if($_SERVER['REQUEST_METHOD']=="POST"){
            if(validate_listing($item, $location, $type, $color, $date, $email)){

                // Image uploading and listing creation logic

                $filename =  $_FILES["imgfile"]["name"];
                $image = "uploads/$filename";

                if (($_FILES["imgfile"]["type"] == "image/gif")|| ($_FILES["imgfile"]["type"] == "image/jpeg") || ($_FILES["imgfile"]["type"] == "image/png")  || ($_FILES["imgfile"]["type"] == "image/pjpeg"))
                {
                    if(file_exists($_FILES["imgfile"]["name"]))
                    {
                        echo "Image file name exists.";

                    }
                    else
                    {
                        echo "blah";
                        move_uploaded_file($_FILES["imgfile"]["tmp_name"], "uploads/$filename");
                        echo "Image upload Successful . <a href='uploads/$filename'>Click here</a> to view the uploaded image";

                        insert_item($dbc, $item, $location, $category, $color, $descr, $date, $email, $status, $image);
                        $_SESSION['inserted'] = true;
                        header('Location: index.php');
                        die("Unauthorized User!");
                    }
                }
                else
                {
                    echo "invalid file.";
                }
                

                

            } 

        }


        ?>

        <!--text field for listing name-->
        <p><input type="text" name="listing-name" placeholder="Listing Name"><span class="required">*</span></p>
        <!--drop down with item types -->
        <p><select name="item-type">
            <option value="" disabled>Item Type</option>
            <option value="Electronics" <?php echo ($type== 'Electronics') ? "selected" : "";  ?> >Electronics</option>
            <option value="Clothing" <?php echo ($type == 'Clothing') ? "selected" : "";  ?> >Clothing</option>
            <option value="School Supplies" <?php echo ($type == 'School Supplies') ? "selected" : "";  ?> >School Supplies</option>
            <option value="Other" <?php echo ($type == 'Other') ? "selected" : "";  ?> >Other</option>
        </select>

        <span class="required" style="margin-right:40px;">*</span>
        <!-- text field for color-->
        <input type="text" name="item-color" placeholder="Item Color"  <?php echo (!empty($color)) ? 'value="' . $color . '"' : ""; ?> >
        <span class="required">*</span></p>
        <p></br>
            <!--generates drop down of locations from database-->
            <select name="location">
                <?php
                echo "<option value='' disabled selected>Location</option>"; 
                dropdown_locations_selected($dbc, $location); ?>
            </select>
            <span class="required">*</span></p>
            <!-- date field for when item was lost -->
            <p>Date found: <input name="date" type="date" value="<?php echo $date; ?>"><span class="required">*</span></p>
            <!-- text field for email address-->
            <p><input type="text" name="email" placeholder="E-Mail Address">

                <span class="required" style="margin-right:40px;">*</span>

                Upload an Image:<input type="file" name="imgfile"><br>
            </p>
            <p><textarea name="further-description" placeholder="Further Description"></textarea></p>
            <input type="text" name="status" value="Found" hidden>
            <input action="action" type="button" class="back-button" value="Back" onclick="history.go(-1);" style="width:75px;"/>
            <!-- submit button-->
            <button type="submit" name="submit" onclick="return confirm('Are you sure all the information is correct?')">Submit</button>
        </form>
    </div>
</body>
</html>