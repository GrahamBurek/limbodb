<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Limbo | Found Search</title>
    <!-- Always include this link to the shared stylesheet. To add more style for a specific page or group of pages, add new link element under shared link! -->
    <link rel="stylesheet" type="text/css" href="templates/sharedStyle.css">
</head>
<body>

    <?php
    // Includes helper functions and connect/populates database
    require('includes/helpers.php');
    require('includes/init.php');
    ?>
    <!-- Navbar at top of page: -->
    <div id="navbar">
        <ul>
            <a href="index.php"><li>LIMBO Lost & Found
            </li></a><a href="index.php"><li>Home
        </li></a><a href="found.php"><li class="current">Found Something?
    </li></a><a href="lost.php"><li>Lost Something?</li></a>
</ul>
</div>

<!-- Main page content: -->
<div id="mainForm">

    <h1>Found something?</h1>
    <h3>See if anyone lost the item you found by giving us some information about it!</h3>
    <br>
    <p style="font-size:12px;"><span class="required">*</span> = Required</p>
    <br>
    <?php 
        # If user came back from found-1.php, tell them to enter something before submitting:
    if (isset($_SESSION['emptyFields'])) {
        
        if ($_SESSION['emptyFields'] == true) {
         
            echo "<p style = color:red>Please fill out the required fields.</p>";
                # Only tell user error message once:
            unset($_SESSION['emptyFields']);
                # Print sticky found.php page:
            ?> 
            <!-- start form -->
            <form action = "found-1.php" method="get" class="itemform">

                <!--drop down with item types -->
                <p><select name="item-type">
                    <option value="" disabled <?php if(empty($_GET['item-type'])){echo "selected";} ?>>Item Type</option>
                    <option value="Electronics" <?php if(isset($_GET['item-type']) && $_GET == "Electronics"){echo "selected";} ?> >Electronics</option>
                    <option value="Clothing" <?php if(isset($_GET['item-type']) && $_GET == "Clothing"){echo "selected";} ?> >Clothing</option>
                    <option value="School Supplies" <?php if(isset($_GET['item-type']) && $_GET == "School Supplies"){echo "selected";} ?> >School Supplies</option>
                    <option value="Other" <?php if(isset($_GET['item-type']) && $_GET == "Other"){echo "selected";} ?> >Other</option>
                </select><span class="required">*</span>

                <!-- text field for color-->
                <input type="text" name="item-color" placeholder="Item Color" value=<?php if(isset($_GET['item-color'])){echo '"' . $_GET['item-color'] . '"';} ?> ><span class="required">*</span></p>
                <p></br>

                    <!--generates drop down of locations from database-->
                    <select name="location">
                        <?php
                        echo "<option value='' disabled>Location</option>";
                        dropdown_locations_sticky($dbc); 
                        ?>
                    </select><span class="required">*</span></p>

                    <!-- Date picker -->
                    <p>Date when found: <input type="date" name="date" value=<?php if(isset($_GET['date'])){ echo $_GET['date']; } ?> ></p>
                    
                    <!-- Hidden values used for the HTTP request -->
                    <input type="hidden" name="submitted" value="yes">
                    
                    <input type="button" class="back-button" onclick="window.location.href='index.php'" value="Back to Home" />
                    
                    <button type="submit">Submit</button>
                </form>

            </div>
        </body>
        </html>
        
        <?php
    }
} else { 
    # Print page normally:
    ?>

    <!-- start form -->
    <form action = "found-1.php" method="get" class="itemform">

        <!--drop down with item types -->
        <p><select name="item-type">
            <option value="" disabled selected>Item Type</option>
            <option value="Electronics">Electronics</option>
            <option value="Clothing">Clothing</option>
            <option value="School Supplies">School Supplies</option>
            <option value="Other">Other</option>
            
        </select><span class="required">*</span>
        <!-- text field for color-->
        <input type="text" name="item-color" placeholder="Item Color"><span class="required">*</span></p>
        <p></br>
            
            <!--generates drop down of locations from database-->
            <select name="location">
                <?php
                echo "<option value='' disabled selected>Location</option>";
                dropdown_locations($dbc); 
                ?>
            </select><span class="required">*</span></p>
            
            <p>Date when found: <input type="date" name="date"></p>

            <!-- Hidden values used for the HTTP request -->
            <input type="hidden" name="submitted" value="yes">
            <input type="button" class="back-button" onclick="window.location.href='index.php'" value="Back to Home" />
            
            <button type="submit">Submit</button>
        </form>

    </div>
</body>
</html>

<?php
    } // End of form.
    ?>