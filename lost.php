<!DOCTYPE html>
<html>
<head>
    <title>Limbo Lost & Found</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="/templates/sharedStyle.css">
</head>
<body>
<!-- Navbar include statement: -->
<?php
require('/templates/navbar.php');
require('/includes/helpers.php');
require('/includes/connect_db.php');
?>
<!-- Main white form for pages: -->
<div id="mainForm">
    <!-- Header and description -->
<h1>Lost something?</h1>
<h3>Help us find your lost item by giving us a brief description of it!</h3>
    <!-- start form -->
<form action = "lost-1.php">
    <!--text field for listing name-->
   <p>Listing Name: <input type="text" name="listing-name" placeholder="Listing Name"></p>
    <!--drop down with item types -->
   <p>Item Type: <select>
        <option value="electronics">Electronic</option>
        <option value="clothing">Clothing</option>
        <option value="accessories">Accessories</option>
        <option value="book">Book</option>
        <option value="other">Other</option>
    </select></p>
    <!-- text field for color-->
    <p>Item Color: <input type="text" name="item-color" placeholder="Color"></p>
    <p>Location where lost (if known): </br>
        <!--generates drop down of locations from database-->
    <select size="7">
        <?php dropdown_locations($dbc); ?>
        </select></p>
    <!-- date field for when item was lost -->
    <p>Date lost (if known): <input type="date">
    <!-- text field for email address-->
    <p>E-Mail Address: <input type="text" name="email" placeholder="E-Mail Address"> </p>
    <!-- submit button-->
    </p><button type="submit">Submit</button>
</form>
</div>
</body>
</html>