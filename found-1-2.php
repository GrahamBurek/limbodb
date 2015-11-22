<!DOCTYPE html>
<html>
<head>
    <title>Limbo Lost & Found</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<!-- Navbar and database include statements: -->
<?php
require('/templates/navbar.php');
require('/includes/helpers.php');
require('/includes/connect_db.php');
?>
<!-- Main white form for pages: -->
<div id="mainForm">
    <!-- Header and description -->
    <h1>Found something?</h1>
    <h3>Create a new listing to help find the item's owner.</h3>
    <!-- start form -->
    <form action = "found-1.php" method="post" enctype="multipart/form-data">
        <!--text field for listing name-->
        <p>Listing Name: <input type="text" name="listing-name" placeholder="Listing Name"></p>
        <!--drop down with item types -->
        <p>Item Type: <select name="item-type">
                <option value="electronics">Electronic</option>
                <option value="clothing">Clothing</option>
                <option value="accessories">Accessories</option>
                <option value="book">Book</option>
                <option value="other">Other</option>
            </select></p>
        <!-- text field for color-->
        <p>Item Color: <input type="text" name="item-color" placeholder="Color"></p>
        <p>Location where found: </br>
            <!--generates drop down of locations from database-->
            <select size="7" name="location">
                <?php dropdown_locations($dbc); ?>
            </select></p>
        <!-- date field for when item was lost -->
        <p>Date found: <input type="date">
            <!-- text field for email address-->
        <p>E-Mail Address: <input type="text" name="email" placeholder="E-Mail Address">
        <?php image_upload()?></p>
        <p><textarea name="further-description" placeholder="Further Description"></textarea></p>

        <!-- submit button-->
        </p><button type="submit" name="submit">Submit</button>
    </form>
    <input type="button" onclick="location.href='index.php';" value="Back to Home" />
</div>
</body>
</html>