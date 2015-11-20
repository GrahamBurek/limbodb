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
<h1>Lost Item Listing</h1>
<p>Help us find your lost item by giving us a description of it!</p>
<form>
   <p> <input type="text" name="listing-name"></p>
   <p>Item Type: <select>
        <option value="electronics">Electronic</option>
        <option value="clothing">Clothing</option>
        <option value="accessories">Accessories</option>
        <option value="book">Book</option>
        <option value="other">Other</option>
    </select></p>
    <p>Item Color: <input type="text" name="item-color"></p>
    <p>Location where lost (if known): <select size="7">
        <?php dropdown_locations($dbc); ?>
        </select></p>
</form>
</div>
</body>
</html>