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
require('/includes/helpers.php');
require('/includes/init.php');

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
        </li></a><a href="found.php"><li>Found Something?
        </li></a><a href="lost.php"><li class="current">Lost Something?</li></a>
    </ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">

    <!-- Header and description -->
    <h1>Lost something?</h1>
    <h3>Create a new listing to help find your lost item.</h3>

    <!-- start form -->
    <form action="results.php" method="post" enctype="multipart/form-data" class="itemform">

        <!--text field for listing name-->
        <p>Listing Name: <input type="text" name="listing-name" placeholder="Listing Name"></p>

        <!--drop down with item types -->
        <p>Item Type: <select name="item-type">
                <option value="Electronics" <?php echo ($_GET['type'] == 'Electronics') ? "selected" : "";  ?> >Electronics</option>
                <option value="Clothing" <?php echo ($_GET['type'] == 'Clothing') ? "selected" : "";  ?> >Clothing</option>
                <option value="School Supplies" <?php echo ($_GET['type'] == 'School Supplies') ? "selected" : "";  ?> >School Supplies</option>
                <option value="Other" <?php echo ($_GET['type'] == 'Other') ? "selected" : "";  ?> >Other</option>
            </select></p>

        <!-- text field for color-->
        <p>Item Color: <input type="text" name="item-color" placeholder="Color"  <?php echo (!empty($color)) ? 'value="' . $color . '"' : ""; ?> ></p>
        <p>Location where lost (if known): </br>

            <!--generates drop down of locations from database-->
            <select name="location">
                 <?php dropdown_locations_selected($dbc, $location); ?>
            </select></p>

        <!-- date field for when item was lost -->
        <p>Date lost: <input name="date" type="date" value="<?php echo $date; ?>">
            <!-- text field for email address-->
        <p>E-Mail Address: <input type="text" name="email" placeholder="E-Mail Address">
            Upload an Image:<input type="file" name="imgfile"><br></p>
        <p><textarea name="further-description" placeholder="Further Description"></textarea></p>
        <input action="action" type="button" class="back-button" value="Back" onclick="history.go(-1);" style="width:75px;"/>
        <input type="text" name="status" value="Lost" hidden>

        <!-- submit button-->
        <button type="submit" name="submit">Submit</button>
    </form>
</div>
</body>
</html>