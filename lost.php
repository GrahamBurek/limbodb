<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Limbo Lost & Found</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>
<!-- Navbar include statement: -->
<?php
    require('includes/helpers.php');
    require('includes/connect_db.php');
?>
<div id="navbar">
    <ul>
        <a href="index.php"><li>Limbo Lost & Found</li></a>
        <a href="index.php"><li >Home</li></a>
        <a href="found.php"><li>Found Something?</li></a>
        <a href="lost.php"><li class="current">Lost Something?</li></a>
    </ul>
</div>
<!-- Main white form for pages: -->
<div id="mainForm">
    <!-- Header and description -->
<h1>Lost something?</h1>
<h3>Search for your lost item by giving us some information about it!</h3>

    <?php 
        # If user came back from lost-1.php, tell them to enter something before submitting:
        if (isset($_SESSION['emptyFields'])) {
        
            if ($_SESSION['emptyFields'] == true) {
                echo "<p style = color:red>Please select at least one field (type, color, or location).</p>";
                # Only tell user error message once:
                unset($_SESSION['emptyFields']);
            }

        }
    ?>

    <!-- start form -->
    <form action = "lost-1.php" method="get">
        <!--drop down with item types -->
        <p>Item Type: <select name="item-type">
                <option value="" disabled selected>Select One</option>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="School Supplies">School Supplies</option>
                <option value="Other">Other</option>
            </select></p>
        <!-- text field for color-->
        <p>Item Color: <input type="text" name="item-color" placeholder="Color"></p>
        <p>Location where lost: </br>
            <!--generates drop down of locations from database-->
            <select name="location">
                <?php
                    echo "<option value='' disabled selected>Select One</option>";
                    dropdown_locations($dbc); 
                ?>
            </select></p>
        <p>Date when lost: <input type="date"></p>
        <input type="button" class="back-button" onclick="location.href='index.php';" value="Back to Home" />
        <!-- submit button-->
        <button type="submit">Submit</button>
    </form>

</div>
</body>
</html>