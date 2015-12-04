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
$type = "";
$color = "";
$location = "";
$date = "";

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
    <h1>Found something?</h1>
    <h3>Create a new listing to help find the item's owner.</h3>
    <!-- start form -->
    <form action="results.php" method="post" enctype="multipart/form-data" class="itemform">
        <!--text field for listing name-->
        <p><input type="text" name="listing-name" placeholder="Listing Name"></p>
        <!--drop down with item types -->
        <p><select name="item-type">
                <option value="" disabled>Item Type</option>
                <option value="Electronics" <?php echo ($type== 'Electronics') ? "selected" : "";  ?> >Electronics</option>
                <option value="Clothing" <?php echo ($type == 'Clothing') ? "selected" : "";  ?> >Clothing</option>
                <option value="School Supplies" <?php echo ($type == 'School Supplies') ? "selected" : "";  ?> >School Supplies</option>
                <option value="Other" <?php echo ($type == 'Other') ? "selected" : "";  ?> >Other</option>
            </select>
        <!-- text field for color-->
        <input type="text" name="item-color" placeholder="Item Color"  <?php echo (!empty($color)) ? 'value="' . $color . '"' : ""; ?> ></p>
        <p></br>
            <!--generates drop down of locations from database-->
            <select name="location">
                <?php
                echo "<option value='' disabled selected>Location</option>"; 
                dropdown_locations_selected($dbc, $location); ?>
            </select></p>
        <!-- date field for when item was lost -->
        <p>Date found: <input name="date" type="date" value="<?php echo $date; ?>">
            <!-- text field for email address-->
        <p><input type="text" name="email" placeholder="E-Mail Address">
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